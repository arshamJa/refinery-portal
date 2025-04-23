<?php

namespace App\Http\Requests;

use App\Rules\farsi_chs;
use App\Rules\Time;
use Illuminate\Foundation\Http\FormRequest;

class MeetingUpdateRequest extends FormRequest
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
            'title' => ['required','string'],
            'unit_organization' => ['required','string','min:5','max:30', new farsi_chs()],
            'scriptorium' => ['required','string', new farsi_chs()],
            'boss' => ['required','string', new farsi_chs()],
            'location' => ['required','string','min:5','max:30'],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'time' => ['required' , 'date_format:H:i' , new Time()],
            'unit_held' => ['required','string','min:5','max:30', new farsi_chs()],
            'treat' => ['required'],
            'guest' => ['nullable'],
            'holders' => ['nullable'] ,
            'applicant' => ['required','string','min:5','max:20', new farsi_chs()],
            'position_organization' => ['required','string','min:5','max:20', new farsi_chs()],
            'signature' => ['required','file','mimes:jpg,png,jpeg,webp,pdf'],
            'reminder' => ['required','numeric'],
        ];
    }
}
