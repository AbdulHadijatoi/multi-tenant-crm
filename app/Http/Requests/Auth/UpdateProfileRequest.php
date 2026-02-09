<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitize($this->name),
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
