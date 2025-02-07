<?php

namespace App\Http\Requests;

use App\Rules\farsi_chs;
use App\Rules\NationalCodeRule;
use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileInformationRequest extends FormRequest
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
            'full_name' => ['bail','required', 'string', 'max:255', new farsi_chs()],
            'n_code' => ['bail','required', 'numeric','digits:10', new NationalCodeRule()],
            'p_code' => ['bail','required', 'numeric', 'digits:6'],
            'phone' => ['bail','required', 'numeric','digits:11','starts_with:09', new PhoneNumberRule()],
            'house_phone' => ['bail','required', 'numeric'],
            'work_phone' => ['bail','required', 'numeric'],
        ];
    }
}
