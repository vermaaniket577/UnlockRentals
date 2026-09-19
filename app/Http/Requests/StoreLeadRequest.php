<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && !$this->has('mobile')) {
            $this->merge(['mobile' => $this->phone]);
        }
        if ($this->has('intent') && !$this->has('purpose')) {
            $this->merge(['purpose' => $this->intent]);
        }
        if ($this->has('bhk_preference') && !$this->has('bedrooms')) {
            $this->merge(['bedrooms' => $this->bhk_preference]);
        }
        if ($this->has('source') && !$this->has('lead_source')) {
            $this->merge(['lead_source' => $this->source]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'min:10', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'property_id' => ['nullable', 'integer', 'exists:properties,id'],
            'lead_source' => ['nullable', 'string', 'max:60'],
            'preferred_city' => ['nullable', 'string', 'max:100'],
            'preferred_locality' => ['nullable', 'string', 'max:150'],
            'property_type' => ['nullable', 'string', 'max:50'],
            'purpose' => ['nullable', 'in:rent,buy,sell'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'string', 'max:20'],
            'furnished_status' => ['nullable', 'string', 'max:50'],
            'move_in_date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:1000'],
            'whatsapp_opt_in' => ['nullable', 'boolean'],
            'marketing_opt_in' => ['nullable', 'boolean'],
            'consent' => ['required', 'accepted'], // Explicit consent checkbox must be checked
            'website_hp' => ['nullable', 'max:0'], // Anti-spam honeypot (bots fill this, humans don't)
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'mobile.required' => 'Please enter a valid mobile number so we can share property details.',
            'consent.accepted' => 'Please agree to the contact consent to receive property details.',
        ];
    }
}
