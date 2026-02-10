<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'password.required' => 'Password is required.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate X-Tenant-Domain header
            if (!$this->hasHeader('X-Tenant-Domain') || empty($this->header('X-Tenant-Domain'))) {
                $validator->errors()->add('X-Tenant-Domain', 'X-Tenant-Domain header is required.');
            }

            // Validate X-License-Key header
            if (!$this->hasHeader('X-License-Key') || empty($this->header('X-License-Key'))) {
                $validator->errors()->add('X-License-Key', 'X-License-Key header is required.');
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->sanitize($this->email),
        ]);
    }

    /**
     * Sanitize input to prevent XSS/HTML injection.
     */
    private function sanitize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        return htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
    }
}
