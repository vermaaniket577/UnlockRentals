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
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('type') === 'plot') {
            $merge = [
                'bedrooms' => null,
                'bathrooms' => null,
            ];
            if (!$this->filled('furnishing')) {
                $merge['furnishing'] = 'unfurnished';
            }
            if (!$this->filled('purpose')) {
                $merge['purpose'] = 'buy';
            }
            if (!$this->filled('price_period')) {
                $merge['price_period'] = 'month';
            }
            $this->merge($merge);
        } elseif ($this->input('type') === 'pg-hostel') {
            $merge = [];
            $merge['purpose'] = 'rent';
            $merge['price_period'] = 'month';
            if (!$this->filled('furnishing')) {
                $merge['furnishing'] = 'fully-furnished';
            }
            if ($this->filled('pg_total_beds')) {
                $merge['bedrooms'] = (int)$this->input('pg_total_beds');
            }
            if ($this->filled('pg_bathroom')) {
                $merge['bathrooms'] = ($this->input('pg_bathroom') === 'attached') ? 1 : 0;
            }

            // Build structured PG details if provided
            $pgLines = [];
            if ($this->filled('pg_occupancy')) {
                $occ = is_array($this->input('pg_occupancy')) ? implode(', ', $this->input('pg_occupancy')) : $this->input('pg_occupancy');
                $pgLines[] = "• Room Sharing / Occupancy: " . trim($occ);
            }
            if ($this->filled('pg_gender')) {
                $pgLines[] = "• Preferred Tenants: " . trim($this->input('pg_gender'));
            }
            if ($this->filled('pg_food')) {
                $pgLines[] = "• Food & Meals: " . trim($this->input('pg_food'));
            }
            if ($this->filled('pg_bathroom')) {
                $bText = ($this->input('pg_bathroom') === 'attached') ? 'Attached Washroom' : 'Shared / Common Washroom';
                $pgLines[] = "• Washroom Type: " . $bText;
            }
            if ($this->filled('pg_curfew')) {
                $pgLines[] = "• Gate Timings: " . trim($this->input('pg_curfew'));
            }
            if ($this->filled('pg_deposit')) {
                $pgLines[] = "• Security Deposit: " . trim($this->input('pg_deposit'));
            }
            if ($this->filled('pg_notice')) {
                $pgLines[] = "• Notice Period: " . trim($this->input('pg_notice'));
            }
            if ($this->filled('pg_amenities')) {
                $amenities = is_array($this->input('pg_amenities')) ? implode(', ', $this->input('pg_amenities')) : $this->input('pg_amenities');
                $pgLines[] = "• PG Facilities: " . trim($amenities);
            }

            if (!empty($pgLines)) {
                $currentDesc = (string)$this->input('description', '');
                if (str_contains($currentDesc, '=== PG & HOSTEL DETAILS ===')) {
                    $parts = explode('=== PG & HOSTEL DETAILS ===', $currentDesc);
                    $newDesc = trim($parts[0]) . "\n\n=== PG & HOSTEL DETAILS ===\n" . implode("\n", $pgLines);
                } else {
                    $newDesc = trim($currentDesc) . "\n\n=== PG & HOSTEL DETAILS ===\n" . implode("\n", $pgLines);
                }
                $merge['description'] = trim($newDesc);
            }

            $this->merge($merge);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'type' => 'required|in:house,shop,pg-hostel,hotel,plot',
            'purpose' => 'nullable|string|in:rent,buy,sell',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'nullable|string|in:crore,lakh,thousand,rupees',
            'price_period' => 'required|in:month,year',
            'state' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:500',
            'alternate_phones' => 'nullable|array|max:10',
            'alternate_phones.*' => 'nullable|string|max:25',
            'bedrooms' => 'nullable|integer|min:0|max:500',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'area_sqft' => 'nullable|integer|min:0',
            'furnishing' => 'nullable|in:unfurnished,semi-furnished,fully-furnished',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'primary_image' => 'nullable|string|max:50',
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
            'pg_occupancy' => 'nullable',
            'pg_gender' => 'nullable|string|max:100',
            'pg_food' => 'nullable|string|max:150',
            'pg_bathroom' => 'nullable|string|max:100',
            'pg_curfew' => 'nullable|string|max:150',
            'pg_deposit' => 'nullable|string|max:100',
            'pg_notice' => 'nullable|string|max:100',
            'pg_amenities' => 'nullable',
            'pg_total_beds' => 'nullable|integer|min:0|max:500',
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
