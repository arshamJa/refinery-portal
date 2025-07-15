<?php

namespace App\Http\Requests;

use App\Rules\Time;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MeetingStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'scriptorium' => ['required'],
            'boss' => ['required'],
            'location' => ['required','string','max:255'],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'time' => ['required', new Time()],
            'unit_held' => ['required','string','max:255'],
            'treat' => ['required','boolean'],
            'guest' => ['nullable'],
            'holders' => ['required']
        ];
    }
}
