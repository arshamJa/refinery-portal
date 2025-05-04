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
        // Normalize
        if (!str_contains($value, ':')) {
            $hour = intval($value); // ensure it's a number
            $value = sprintf('%02d:00', $hour);
        }

        // Step 1: Validate time format (HH:MM)
        if (!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
            $fail('فرمت زمان معتبر نیست. فرمت درست: HH:MM');
            return;
        }

        // Step 2: Extract hour and minute
        list($hour, $minute) = explode(':', $value);
        $hour = intval($hour);
        $minute = intval($minute);

        // Current Jalali time
        $j_hour = jgetdate()['hours'];
        $j_minute = jgetdate()['minutes'];

        // Jalali today date
        [$jaYear, $jaMonth, $jaDay] = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        // Request date
        $requestYear = intval(substr(request('date'), 0, 4));
        $requestMonth = intval(substr(request('date'), 5, 2));
        $requestDay = intval(substr(request('date'), 8));

        // If selected date is today, ensure time is in the future
        $isToday = ($requestYear == $jaYear && $requestMonth == $jaMonth && $requestDay == $jaDay);
        if ($isToday && ($hour < $j_hour || ($hour == $j_hour && $minute <= $j_minute))) {
            $fail('ساعت باید بعد از زمان فعلی باشد');
        }
    }
}
