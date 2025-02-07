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

            $year = intval(substr($value, 0, -6));
            $month = intval(substr($value, 5, -3));
            $day = intval(substr($value, 8));

            $timestamp = jmktime(
                jgetdate()['hours'], jgetdate()['minutes'], jgetdate()['seconds'],
                $month, $day, $year
            );

            if (time() > $timestamp) {
                $fail('تاریخ آزمون باید بعد از الان باشد');
            }
        }
    }
}
