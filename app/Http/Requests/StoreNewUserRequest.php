<?php

namespace App\Http\Requests;

use App\Rules\farsi_chs;
use App\Rules\NationalCodeRule;
use App\Rules\PhoneNumberRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreNewUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['bail', 'required'],
            'permissions' => ['bail', 'required'],
            'full_name' => ['bail', 'required', 'string', 'min:5', 'max:255', new farsi_chs()],
            'p_code' => ['bail', 'required', 'numeric', 'digits:6'],
            'n_code' => ['bail', 'required', 'numeric', 'digits:10', new NationalCodeRule()],
            'phone' => ['bail', 'required', 'digits:11', new PhoneNumberRule()],
            'house_phone' => ['bail', 'required', 'numeric'],
            'work_phone' => ['bail', 'required', 'numeric'],
            'position' => ['bail', 'required', 'string','max:255',new farsi_chs()],
            'departmentId' => ['bail', 'required'],
            'password' => ['bail', 'required', 'numeric', 'digits:8', Rules\Password::defaults()],
        ];
    }
}
