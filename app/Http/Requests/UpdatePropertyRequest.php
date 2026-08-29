<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();
        $property = $this->route('property');

        if (is_numeric($property) || is_string($property)) {
            $property = \App\Models\Property::find($property);
        }

        if (!$property) {
            return false;
        }

        return (int)$user->id === (int)$property->user_id || 
               (method_exists($user, 'isAdmin') && $user->isAdmin()) || 
               $user->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'type' => 'required|in:house,shop,pg-hostel,hotel',
            'purpose' => 'nullable|string|in:rent,buy,sell',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'price_period' => 'required|in:month,year',
            'state' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'area_sqft' => 'nullable|integer|min:0',
            'furnishing' => 'required|in:unfurnished,semi-furnished,fully-furnished',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'primary_image' => 'nullable|integer|min:0',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:property_images,id',
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm,m4v|max:256000',
            'videos' => 'nullable|array|max:10',
            'videos.*' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm,m4v|max:256000',
            'uploaded_video_paths' => 'nullable|array|max:10',
            'uploaded_video_paths.*' => 'nullable|string|max:500',
            'video_url' => 'nullable|string|max:500',
            'video_urls' => 'nullable|array|max:10',
            'video_urls.*' => 'nullable|string|max:500',
            'remove_video' => 'nullable|boolean',
            'remove_video_indexes' => 'nullable|array',
            'remove_video_indexes.*' => 'integer',
        ];
    }

    /**
     * Configure the validator instance to enforce either video or photo on update.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $property = $this->route('property');
            if (is_numeric($property) || is_string($property)) {
                $property = \App\Models\Property::find($property);
            }
            if (!$property) return;

            // Existing images after removals
            $removedImageIds = $this->input('remove_images', []);
            $remainingExistingImagesCount = $property->images()->whereNotIn('id', $removedImageIds)->count();
            $newImagesCount = !empty($this->file('images')) ? count($this->file('images')) : 0;
            $totalImages = $remainingExistingImagesCount + $newImagesCount;

            // Existing videos after removals
            $existingVideos = $property->allVideoUrls();
            $removeAllVideos = (bool)$this->input('remove_video', false);
            $removedVideoIndexes = $this->input('remove_video_indexes', []);

            $remainingExistingVideosCount = 0;
            if (!$removeAllVideos) {
                $remainingExistingVideosCount = count(array_filter($existingVideos, function($idx) use ($removedVideoIndexes) {
                    return !in_array($idx, $removedVideoIndexes);
                }, ARRAY_FILTER_USE_KEY));
            }

            $newVideoFilesCount = (!empty($this->file('videos')) ? count($this->file('videos')) : 0) + (!empty($this->file('video')) ? 1 : 0);
            $newPreUploadedCount = (!empty($this->input('uploaded_video_paths')) ? count(array_filter($this->input('uploaded_video_paths'))) : 0);
            $newVideoUrlsCount = (!empty($this->input('video_urls')) ? count(array_filter($this->input('video_urls'))) : 0) + (!empty(trim($this->input('video_url', ''))) ? 1 : 0);
            $totalVideos = $remainingExistingVideosCount + $newVideoFilesCount + $newPreUploadedCount + $newVideoUrlsCount;

            if ($totalImages === 0 && $totalVideos === 0) {
                $validator->errors()->add('images', 'Please upload at least one photo or video tour for your property listing.');
            }
        });
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'images.*.max' => 'Each image must be under 5MB.',
            'images.*.image' => 'Only image files are allowed.',
            'images.*.mimes' => 'Accepted formats: JPEG, PNG, JPG, WebP.',
            'video.max' => 'Property video file size must not exceed 250MB.',
            'video.mimes' => 'Video must be in MP4, WebM, MOV, or M4V format.',
            'videos.max' => 'You can upload a maximum of 10 video clips.',
            'videos.*.max' => 'Each video clip must be under 250MB.',
            'videos.*.mimes' => 'Video clips must be in MP4, WebM, MOV, or M4V format.',
            'video_urls.max' => 'You can attach a maximum of 10 video links.',
            'description.min' => 'Please provide a detailed description (at least 20 characters).',
        ];
    }
}
