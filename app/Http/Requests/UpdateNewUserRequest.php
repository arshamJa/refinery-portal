<?php

namespace App\Http\Requests;

use App\Rules\farsi_chs;
use App\Rules\NationalCodeRule;
use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateNewUserRequest extends FormRequest
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
            'role' => ['bail', 'required'],
            'permissions' => ['nullable'],
            'full_name' => ['bail', 'required', 'string', 'min:5', 'max:255', new farsi_chs()],
            'p_code' => ['bail', 'required', 'numeric', 'digits:6'],
            'n_code' => ['bail', 'required', 'numeric', 'digits:10', new NationalCodeRule()],
            'phone' => ['bail', 'required', 'digits:11', new PhoneNumberRule()],
            'house_phone' => ['bail', 'required', 'numeric'],
            'work_phone' => ['bail', 'required', 'numeric'],
            'position' => ['bail', 'required', 'string','max:255',new farsi_chs()],
            'department' => ['required'],
            'password' => ['bail', 'nullable', 'numeric', 'digits:8', Rules\Password::defaults()],
        ];
    }
}
