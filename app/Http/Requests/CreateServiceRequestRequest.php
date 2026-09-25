<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:professional_categories,id'],
            'service_id' => ['nullable', 'exists:professional_services,id'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-]{10,20}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'locality' => ['nullable', 'string', 'max:150'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:500'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'string', 'max:50'],
            'budget' => ['nullable', 'string', 'max:50'],
            'direct_professional_id' => ['nullable', 'exists:professionals,id'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
        ];
    }
}
