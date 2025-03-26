<?php

namespace App\Http\Requests;

use App\Rules\farsi_chs;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' => ['bail', 'required', 'string', 'max:255', new farsi_chs()],
            'p_code' => ['bail', 'required', 'numeric', 'digits:6'],
            'password' => ['required', 'numeric', 'digits:8']
        ];
    }
}
