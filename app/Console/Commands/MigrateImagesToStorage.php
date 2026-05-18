<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateImagesToStorage extends Command
{
    protected $signature   = 'images:migrate-to-storage';
    protected $description = 'Migrate property images from binary DB columns to disk storage for maximum speed.';

    public function handle(): int
    {
        $images = PropertyImage::whereNotNull('image_data')
            ->whereNull('path')
            ->orWhere('path', '')
            ->get();

        if ($images->isEmpty()) {
            $this->info('No images to migrate. All images already on disk.');
            return 0;
        }

        $this->info("Found {$images->count()} image(s) to migrate...");
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $success = 0;
        $failed  = 0;

        foreach ($images as $image) {
            try {
                // Detect MIME type from binary blob
                $finfo    = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($image->image_data);

                $extension = match (true) {
                    str_contains($mimeType, 'png')  => 'png',
                    str_contains($mimeType, 'webp') => 'webp',
                    str_contains($mimeType, 'gif')  => 'gif',
                    default                         => 'jpg',
                };

                // Build a unique storage path
                $filename = 'properties/' . $image->property_id . '/' . Str::uuid() . '.' . $extension;

                // Write binary data to disk
                Storage::disk('public')->put($filename, $image->image_data);

                // Update DB row: set path, clear blob to free space
                $image->update([
                    'path'       => $filename,
                    'image_data' => null,
                ]);

                $success++;
            } catch (\Throwable $e) {
                $this->newLine();
                $this->warn("Failed to migrate image ID {$image->id}: {$e->getMessage()}");
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Migration complete! Migrated: {$success} | Failed: {$failed}");

        if ($success > 0) {
            $this->info('Run [php artisan storage:link] if you have not already done so.');
        }

        return 0;
    }
}
