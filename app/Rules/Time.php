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
            $hour = intval(substr($value, 0, -3));
            $minute = intval(substr($value, 3));

            $j_hour = jgetdate()['hours'];
            $j_minute = jgetdate()['minutes'];

            $subtraction = $minute - $j_minute;

            if ($hour == jgetdate()['hours']) {
                if ($minute <= jgetdate()['minutes']) {
                    $fail('ساعت باید بعد از الان باشد');
                }
            }
        }

    }
}
