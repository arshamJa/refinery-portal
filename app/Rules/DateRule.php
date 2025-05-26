<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use function App\Http\Controllers\isGregorian;

class DateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $gregorianPattern = '/^(19|20)\d\d\/(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01])$/';

        if (preg_match($gregorianPattern, $value)) {
            $fail('تاریخ با فرمت شمسی وارد کنید');
        } else {
            $year = intval(substr($value, 0, 4));
            $month = intval(substr($value, 5, 2));
            $day = intval(substr($value, 8));

            $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
            $jaYear = intval($jalaliNow[0]);
            $jaMonth = intval($jalaliNow[1]);
            $jaDay = intval($jalaliNow[2]);

            if ($year < $jaYear || ($year == $jaYear && $month < $jaMonth) || ($year == $jaYear && $month == $jaMonth && $day < $jaDay)) {
                $fail('تاریخ باید بعد از امروز باشد');
            }
        }
    }
}
