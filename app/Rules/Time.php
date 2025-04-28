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

        // Step 1: Check if the time format matches the regex
        if (!preg_match('/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
            $fail('فرمت زمان معتبر نیست. فرمت درست: HH:MM');
            return; // If the format doesn't match, stop further validation
        }

        // Step 2: Extract hour and minute from the value
        $hour = intval(substr($value, 0, 2)); // Extract hour
        $minute = intval(substr($value, 3)); // Extract minute

        // Get current time in Jalali
        $j_hour = jgetdate()['hours'];
        $j_minute = jgetdate()['minutes'];

        // Convert current Gregorian date to Jalali date for comparison
        $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $jaYear = intval($jalaliNow[0]);
        $jaMonth = intval($jalaliNow[1]);
        $jaDay = intval($jalaliNow[2]);

        // Extract year, month, day from the request's date
        $requestYear = intval(substr(request('date'), 0, 4));
        $requestMonth = intval(substr(request('date'), 5, 2));
        $requestDay = intval(substr(request('date'), 8));

        // Check if the selected date is today
        $isToday = ($requestYear == $jaYear && $requestMonth == $jaMonth && $requestDay == $jaDay);

        // If the selected date is today, validate that the time is in the future
        if ($isToday &&  ($hour < $j_hour || ($hour == $j_hour && $minute <= $j_minute)) ) {
            $fail('ساعت باید بعد از زمان فعلی باشد');
        }

    }
}
