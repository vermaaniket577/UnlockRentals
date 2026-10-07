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
        if ($this->has('full_name') && !$this->has('name')) {
            $this->merge(['name' => $this->full_name]);
        }
        if ($this->has('student_name') && !$this->has('name')) {
            $this->merge(['name' => $this->student_name]);
        }
        if ($this->has('candidate_name') && !$this->has('name')) {
            $this->merge(['name' => $this->candidate_name]);
        }
        if ($this->has('phone') && !$this->has('mobile')) {
            $this->merge(['mobile' => $this->phone]);
        }
        if ($this->has('contact') && !$this->has('mobile')) {
            $this->merge(['mobile' => $this->contact]);
        }
        if ($this->has('student_mobile') && !$this->has('mobile')) {
            $this->merge(['mobile' => $this->student_mobile]);
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

        // Handle academic / course / admission parameters
        if ($this->filled('course') || $this->filled('program') || $this->filled('admission') || $this->filled('college')) {
            $academicParts = array_filter([
                $this->input('course'),
                $this->input('program'),
                $this->input('degree'),
                $this->input('college'),
                $this->input('university'),
            ]);
            $academic = implode(' • ', $academicParts);
            $existingMsg = $this->input('message') ?: $this->input('enquiry') ?: $this->input('query') ?: '';
            $composite = $academic ? ($existingMsg ? "Course: {$academic}\nEnquiry: {$existingMsg}" : "Course: {$academic}") : $existingMsg;
            $this->merge([
                'message' => $composite,
                'lead_source' => $this->input('lead_source') ?: 'admission',
                'property_type' => $this->input('property_type') ?: 'admission',
                'purpose' => 'rent',
            ]);
        }
    }

    public function rules(): array
    {
        $isApi = $this->is('api/*') || $this->expectsJson() || $this->wantsJson();

        return [
            'name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'min:10', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'property_id' => ['nullable', 'integer', 'exists:properties,id'],
            'lead_source' => ['nullable', 'string', 'max:60'],
            'preferred_city' => ['nullable', 'string', 'max:100'],
            'preferred_locality' => ['nullable', 'string', 'max:150'],
            'property_type' => ['nullable', 'string', 'max:50'],
            'purpose' => ['nullable', 'string', 'max:50'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'string', 'max:20'],
            'furnished_status' => ['nullable', 'string', 'max:50'],
            'move_in_date' => ['nullable', 'date'],
            'message' => ['nullable', 'string', 'max:1000'],
            'whatsapp_opt_in' => ['nullable', 'boolean'],
            'marketing_opt_in' => ['nullable', 'boolean'],
            'consent' => $isApi ? ['nullable'] : ['required', 'accepted'],
            'website_hp' => ['nullable', 'max:0'],
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
