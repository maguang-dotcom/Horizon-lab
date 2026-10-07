<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'contact_number'=> ['required', 'string', 'max:30'],
            'email'         => ['required', 'email', 'max:255'],
            'subject'       => ['required', Rule::in([
                'Servicing',
                'AD Hoc',
                'Partnerships',
                'General Enquiry',
                'Careers',
                'B2B',
                'Product Enquiry',
            ])],
            'enquiry'       => ['required', 'string', 'max:5000'],
            'consent'       => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => 'You must agree to the privacy policy to submit this form.',
        ];
    }
}