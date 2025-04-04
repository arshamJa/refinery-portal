<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Time implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            $fail('فیلد :attribute اجباری است');
        } else {
            $hour = intval(substr($value, 0, 2)); // Extract hour
            $minute = intval(substr($value, 3)); // Extract minute

            $j_hour = jgetdate()['hours'];
            $j_minute = jgetdate()['minutes'];

            // Convert Jalali date to today’s date for comparison
            $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
            $jaYear = intval($jalaliNow[0]);
            $jaMonth = intval($jalaliNow[1]);
            $jaDay = intval($jalaliNow[2]);

            // Extract year, month, day from request (assumes input format: YYYY/MM/DD)
            $requestYear = intval(substr(request('date'), 0, 4));
            $requestMonth = intval(substr(request('date'), 5, 2));
            $requestDay = intval(substr(request('date'), 8));

            // Check if the selected date is today
            $isToday = ($requestYear == $jaYear && $requestMonth == $jaMonth && $requestDay == $jaDay);

            // If the selected date is today, validate that the time is in the future
            if ($isToday && ($hour < $j_hour || ($hour == $j_hour && $minute <= $j_minute))) {
                $fail('ساعت باید بعد از زمان فعلی باشد');
            }
        }

//        if ($value === null) {
//            $fail('فیلد :attribute اجباری است');
//        } else {
//            $hour = intval(substr($value, 0, -3));
//            $minute = intval(substr($value, 3));
//
//            $j_hour = jgetdate()['hours'];
//            $j_minute = jgetdate()['minutes'];
//
//            $subtraction = $minute - $j_minute;
//
//            if ($hour == jgetdate()['hours']) {
//                if ($minute <= jgetdate()['minutes']) {
//                    $fail('ساعت باید بعد از الان باشد');
//                }
//            }
//        }

    }
}
