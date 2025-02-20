<?php

namespace App\Http\Requests;

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
            'title' => ['required','string','min:5','max:20'],
            'unit_organization' => ['required','string','min:5','max:30', new farsi_chs()],
            'scriptorium' => ['required','string', new farsi_chs()],
            'location' => ['required','string','min:5','max:30', new farsi_chs()],
//            'date' => ['required','date_format:Y/m/d' , new DateRule()],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'time' => ['required' , 'date_format:H:i' , new Time()],
            'unit_held' => ['required','string','min:5','max:30', new farsi_chs()],
            'treat' => ['required'],
            'guest' => ['nullable'],
            'holders' => ['required'] ,
            'applicant' => ['required','string','min:5','max:20', new farsi_chs()],
            'position_organization' => ['required','string','min:5','max:20', new farsi_chs()],
            'signature' => ['required','file','mimes:jpg,png,jpeg,webp,pdf'],
            'reminder' => ['required','numeric'],
        ];
    }
}
