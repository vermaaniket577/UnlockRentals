<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportProfessionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'in:fake_profile,wrong_info,fraud,abusive_behaviour,spam,incorrect_contact,other'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'reporter_name' => ['nullable', 'string', 'max:120'],
            'reporter_contact' => ['nullable', 'string', 'max:100'],
        ];
    }
}
