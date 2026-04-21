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
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'price_period' => 'required|in:month,year',
            'state' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'area_sqft' => 'nullable|integer|min:0',
            'furnishing' => 'required|in:unfurnished,semi-furnished,fully-furnished',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'primary_image' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'images.*.max' => 'Each image must be under 2MB.',
            'images.*.image' => 'Only image files are allowed.',
            'images.*.mimes' => 'Accepted formats: JPEG, PNG, JPG, WebP.',
            'description.min' => 'Please provide a detailed description (at least 20 characters).',
            'images.required' => 'Please upload at least one property image.',
            'images.min' => 'Please upload at least one property image.',
        ];
    }
}
