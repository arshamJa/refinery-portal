<?php

namespace App\Http\Requests;

use App\Rules\farsi_chs;
use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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
            'role' => ['required', 'string', 'max:40', new farsi_chs()],
            'permissions' => ['required', 'array'], // Ensure permissions are an array
            'permissions.*' => ['exists:permissions,id'], // Validate each permission ID exists
        ];
    }
}
