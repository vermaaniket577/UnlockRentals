<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'reviewer_name' => ['required', 'string', 'max:120'],
            'reviewer_phone' => ['nullable', 'string', 'max:20'],
            'title' => ['nullable', 'string', 'max:190'],
            'review' => ['required', 'string', 'min:10', 'max:2000'],
            'service_request_id' => ['nullable', 'exists:service_requests,id'],
        ];
    }
}
