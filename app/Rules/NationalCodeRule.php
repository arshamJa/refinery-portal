<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NationalCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // Ensure the code is exactly 10 digits
        $patternNumber = '/^\d{10}$/';

        // Ensure all digits are not the same
        $patternDigit = '/^(\d)\1*$/';

        if (!preg_match($patternNumber, $value)) {
            $fail('تعداد ارقام باید 10 باشد');
        }elseif(preg_match($patternDigit, $value)){
            $fail('ارقام نباید تکراری باشد');
        }
        else {
            $sum = 0;
            $j = 10;
            for ($i = 0; $i < 9; $i++) {
                $sum += intval($value[$i]) * $j;
                $j--;
            }
            $remain = $sum % 11;
            if ($remain < 2) {
                if ($remain != intval($value[9])) {
                    $fail(':attribute اعتبار ندارد.');
                }
            } else {
                if ((11 - $remain) != intval($value[9])) {
                    $fail(':attribute اعتبار ندارد.');
                }
            }
        }


    }
}
