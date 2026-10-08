<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public const INTERESTS = [
        'Enterprise Network Architecture',
        'Cybersecurity & Server Protection Operations',
        'Oracle APEX ERP & Software Automation',
        'Cloud Infrastructure (IaaS) Scaling',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:254'],
            'interest' => ['nullable', 'string', Rule::in(self::INTERESTS)],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'company_website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Enter your business email address.',
            'email.email' => 'Enter a complete email address, such as name@company.ca.',
            'interest.in' => 'Choose one of the available service areas.',
            'message.required' => 'Tell us briefly about the environment or project.',
            'message.min' => 'Add a little more detail so our team can understand your request.',
            'message.max' => 'Keep the technical description to 2,000 characters or fewer.',
            'company_website.max' => 'We could not accept this request. Please try again.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => trim((string) $this->input('email')),
            'interest' => $this->filled('interest') ? trim((string) $this->input('interest')) : null,
            'message' => trim((string) $this->input('message')),
        ]);
    }
}
