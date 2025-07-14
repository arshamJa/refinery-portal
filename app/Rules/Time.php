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
        // Normalize time if needed
        if (!str_contains($value, ':')) {
            $hour = intval($value);
            $value = sprintf('%02d:00', $hour);
        }
        // Validate time format (HH:MM)
        if (!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
            $fail('فرمت زمان معتبر نیست. فرمت درست: HH:MM');
            return;
        }
        $year = request()->input('year');
        $month = request()->input('month');
        $day = request()->input('day');

        if (!$year || !$month || !$day) {
            return;
        }

        // Let's get the current Jalali date (assuming gregorian_to_jalali() exists)
        [$jaYear, $jaMonth, $jaDay] = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        // Check if input date is today
        if ((int)$year === (int)$jaYear && (int)$month === (int)$jaMonth && (int)$day === (int)$jaDay) {

            [$inputHour, $inputMinute] = explode(':', $value);
            $inputHour = (int) $inputHour;
            $inputMinute = (int) $inputMinute;

            $nowHour = (int) now()->format('H');
            $nowMinute = (int) now()->format('i');

            if ($inputHour < $nowHour || ($inputHour === $nowHour && $inputMinute <= $nowMinute)) {
                $fail('ساعت باید بعد از زمان فعلی باشد');
                return;
            }
        }
    }

}
