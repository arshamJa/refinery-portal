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
            'role' => ['bail','required', 'exists:roles,id'],
            'permissions' => ['nullable'],
            'full_name' => ['bail', 'required', 'string', 'min:5', 'max:255', new farsi_chs()],
            'p_code' => ['bail', 'required', 'string',$this->pCodeRule()],
            'n_code' => ['bail', 'required', 'numeric', 'digits:10', new NationalCodeRule()],
            'phone' => ['bail','required', 'numeric', 'digits:11',new PhoneNumberRule()],
            'house_phone' => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'work_phone'  => ['bail', 'required', 'numeric', 'digits_between:5,10'],
            'position' => ['bail', 'required', 'string','max:255'],
            'department' => ['bail', 'required'],
            'organization' => ['bail','nullable'],
            'signature' => ['bail', 'nullable'],
        ];
    }
    protected function pCodeRule()
    {
        return function ($attribute, $value, $fail) {
            $roleId = $this->input('role');
            $role = \App\Models\Role::find($roleId);

            // Allow "admin" regardless of role
            if ($value === 'admin') {
                return true;
            }
            // Allow any p_code if role is ADMIN or SUPER_ADMIN
            if ($role && in_array($role->name, [\App\Enums\UserRole::ADMIN->value, \App\Enums\UserRole::SUPER_ADMIN->value])) {
                return true;
            }
            // Otherwise, enforce numeric and 6 digits
            if (!is_numeric($value) || strlen($value) !== 6) {
                $fail('کد پرسنلی باید ۶ رقم باشد باشد.');
            }
            return true;
        };
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
        return $value ? preg_replace('/(?!^\+)[^\d]/', '', $value) : null;
    }
}
