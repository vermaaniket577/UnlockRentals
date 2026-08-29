<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->isTenant());
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
            'video' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm,m4v|max:256000',
            'videos' => 'nullable|array|max:10',
            'videos.*' => 'nullable|file|mimes:mp4,mov,ogg,qt,webm,m4v|max:256000',
            'uploaded_video_paths' => 'nullable|array|max:10',
            'uploaded_video_paths.*' => 'nullable|string|max:500',
            'video_url' => 'nullable|string|max:500',
            'video_urls' => 'nullable|array|max:10',
            'video_urls.*' => 'nullable|string|max:500',
        ];
    }

    /**
     * Configure the validator instance to enforce either video or photo.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasImages = !empty($this->file('images')) && count($this->file('images')) > 0;
            $hasVideoFiles = (!empty($this->file('videos')) && count($this->file('videos')) > 0) || !empty($this->file('video'));
            $hasPreUploadedVideos = (!empty($this->input('uploaded_video_paths')) && count(array_filter($this->input('uploaded_video_paths'))) > 0);
            $hasVideoUrls = (!empty($this->input('video_urls')) && count(array_filter($this->input('video_urls'))) > 0) || !empty(trim($this->input('video_url', '')));

            if (!$hasImages && !$hasVideoFiles && !$hasPreUploadedVideos && !$hasVideoUrls) {
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
            'images.required' => 'Please upload at least one photo or video tour.',
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
