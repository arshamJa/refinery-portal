<?php

namespace App\Http\Requests\meeting;

use App\Rules\DateRule;
use App\Rules\farsi_chs;
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
            'title' => ['required','string'],
            'unit_organization' => ['required','string','min:5', new farsi_chs()],
            'scriptorium' => ['required','string', new farsi_chs()],
            'location' => ['required','string'],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'time' => ['required' , 'date_format:H:i' , new Time()],
            'unit_held' => ['required','string', new farsi_chs()],
            'treat' => ['required'],
            'guest' => ['nullable'],
            'holders' => ['required'] ,
            'applicant' => ['required','string', new farsi_chs()],
            'position_organization' => ['required','string',new farsi_chs()],
        ];
    }
}
