<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhoneRequest extends FormRequest
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
            'phone' => ['required', 'numeric', 'digits:11'],
            'house_phone' => ['required', 'numeric', 'max:15'],
            'work_phone' => ['required', 'numeric', 'max:15'],
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => $this->cleanPhone($this->phone),
            'house_phone' => $this->cleanPhone($this->house_phone),
            'work_phone' => $this->cleanPhone($this->work_phone),
        ]);
    }
    private function cleanPhone($value): ?string
    {
        return $value ? preg_replace('/[^\d+]/', '', $value) : null;
    }
}
