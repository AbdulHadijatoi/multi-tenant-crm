<?php

namespace App\Http\Requests\Tenant\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
        ];
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
