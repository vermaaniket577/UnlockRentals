<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterProfessionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Personal
            'full_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-]{10,20}$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\s\-]{10,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],

            // Business & Services
            'business_name' => ['required', 'string', 'max:190'],
            'category_id' => ['required', 'exists:professional_categories,id'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['exists:professional_services,id'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:professional_services,id'],
            'years_experience' => ['required', 'integer', 'min:0', 'max:60'],
            'description' => ['nullable', 'string', 'max:3000'],

            // Location
            'state' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'locality' => ['nullable', 'string', 'max:150'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:500'],
            'service_radius_km' => ['nullable', 'integer', 'min:1', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // Extra Service Areas
            'additional_locations' => ['nullable', 'array'],
            'additional_locations.*' => ['string', 'max:150'],

            // Preferences & Charges
            'starting_price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'price_type' => ['required', 'in:hourly,per_visit,per_service,negotiable,contact'],
            'home_visit' => ['nullable', 'boolean'],
            'emergency_service' => ['nullable', 'boolean'],
            'available_today' => ['nullable', 'boolean'],

            // Private Document verification
            'document_type' => ['nullable', 'in:id_proof,address_proof,certificate,business_registration,other'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'document_file' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf,webp', 'max:8192'],
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'professional category',
            'service_ids' => 'services',
            'years_experience' => 'years of experience',
            'service_radius_km' => 'service radius',
            'document_file' => 'verification document',
        ];
    }
}
