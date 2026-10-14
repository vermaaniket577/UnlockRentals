<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Maximum width and height for property photos.
     * 1600px provides crisp Retina resolution for property galleries
     * while keeping WebP file sizes around 60KB - 120KB.
     */
    public const MAX_DIMENSION = 1600;

    /**
     * WebP compression quality (0-100).
     * 80 provides indistinguishable visual quality with 85-95% file size reduction.
     */
    public const WEBP_QUALITY = 80;

    /**
     * Optimize an uploaded property image and store it directly in public storage.
     * Auto-rotates using EXIF, resizes proportionally, and converts to WebP.
     *
     * @param UploadedFile|string $file
     * @param string $destinationDir e.g. 'properties/12'
     * @return string Relative storage path e.g. 'properties/12/abc.webp'
     */
    public static function optimizeAndStore($file, string $destinationDir): string
    {
        $storageDir = storage_path('app/public/' . trim($destinationDir, '/'));
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }

        $sourcePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $originalExt = strtolower($file instanceof UploadedFile ? $file->getClientOriginalExtension() : pathinfo($sourcePath, PATHINFO_EXTENSION));

        // Generate unique filename
        $filename = Str::random(32) . '.webp';
        $targetPath = $storageDir . DIRECTORY_SEPARATOR . $filename;
        $relativeStoragePath = trim($destinationDir, '/') . '/' . $filename;

        // If GD with WebP support is available, optimize and convert
        if (extension_loaded('gd') && function_exists('imagewebp') && is_readable($sourcePath)) {
            try {
                $optimized = self::processImageFileToWebp($sourcePath, $targetPath, self::MAX_DIMENSION, self::WEBP_QUALITY);
                if ($optimized && file_exists($targetPath) && filesize($targetPath) > 0) {
                    return $relativeStoragePath;
                }
            } catch (\Throwable $e) {
                Log::warning("ImageOptimizer failed to convert to WebP: " . $e->getMessage());
            }
        }

        // Fallback: standard file storage if GD/WebP is not available or failed
        if ($file instanceof UploadedFile) {
            return $file->store($destinationDir, 'public');
        }

        $fallbackFilename = Str::random(32) . '.' . ($originalExt ?: 'jpg');
        $fallbackTarget = $storageDir . DIRECTORY_SEPARATOR . $fallbackFilename;
        @copy($sourcePath, $fallbackTarget);
        return trim($destinationDir, '/') . '/' . $fallbackFilename;
    }

    /**
     * Convert binary image data (e.g. from MySQL BLOB) to optimized WebP binary.
     *
     * @param string $binaryData
     * @param int $maxDimension
     * @param int $quality
     * @return string|null Optimized WebP binary or null on failure
     */
    public static function optimizeBinaryToWebp(string $binaryData, int $maxDimension = 1200, int $quality = 80): ?string
    {
        if (!extension_loaded('gd') || !function_exists('imagewebp') || empty($binaryData)) {
            return null;
        }

        try {
            $src = @imagecreatefromstring($binaryData);
            if (!$src) {
                return null;
            }

            $src = self::autoRotateGdImage($src, $binaryData);

            $width = imagesx($src);
            $height = imagesy($src);

            if ($width > $maxDimension || $height > $maxDimension) {
                $ratio = min($maxDimension / $width, $maxDimension / $height);
                $newWidth = (int) round($width * $ratio);
                $newHeight = (int) round($height * $ratio);

                $dst = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($src);
                $src = $dst;
            }

            ob_start();
            imagewebp($src, null, $quality);
            $webpData = ob_get_clean();
            imagedestroy($src);

            return $webpData ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Process an image file from disk and save as optimized WebP.
     */
    protected static function processImageFileToWebp(string $sourcePath, string $targetPath, int $maxDimension, int $quality): bool
    {
        $info = @getimagesize($sourcePath);
        if (!$info) {
            return false;
        }

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            IMAGETYPE_GIF  => @imagecreatefromgif($sourcePath),
            default        => @imagecreatefromstring(@file_get_contents($sourcePath)),
        };

        if (!$src) {
            return false;
        }

        // Auto rotate for JPEGs with EXIF orientation
        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);
            if (!empty($exif['Orientation'])) {
                $src = match ($exif['Orientation']) {
                    3 => imagerotate($src, 180, 0),
                    6 => imagerotate($src, -90, 0),
                    8 => imagerotate($src, 90, 0),
                    default => $src,
                };
            }
        }

        $width = imagesx($src);
        $height = imagesy($src);

        // Resize proportionally if dimensions exceed max
        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $dst = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($src);
            $src = $dst;
        }

        $success = imagewebp($src, $targetPath, $quality);
        imagedestroy($src);

        return $success;
    }

    /**
     * Auto rotate an image from binary data if EXIF data is present.
     */
    protected static function autoRotateGdImage($image, string $binaryData)
    {
        if (!function_exists('exif_read_data') || !function_exists('imagerotate')) {
            return $image;
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $binaryData);
        rewind($stream);
        $exif = @exif_read_data($stream);
        fclose($stream);

        if (!empty($exif['Orientation'])) {
            return match ($exif['Orientation']) {
                3 => imagerotate($image, 180, 0),
                6 => imagerotate($image, -90, 0),
                8 => imagerotate($image, 90, 0),
                default => $image,
            };
        }

        return $image;
    }
}
