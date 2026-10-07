<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'facility'
            && $this->user()->currentFacility?->status === 'approved';
    }

    public function rules(): array
    {
        return [
            'equipment_id' => ['required', 'exists:equipment,id'],
            'problem_classification' => ['required', 'string', 'max:255'],
            'diagnostic_notes' => ['required', 'string', 'max:1000'],
            'urgency_tier' => ['required', Rule::in(['standard', 'high', 'critical'])],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ];
    }
}
