<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class farsi_chs implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fa_chs = ['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک',
            'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 'ئ', ' '];
        $chs = mb_str_split($value);
        $s = 0;
        foreach ($chs as $ch) {
            foreach ($fa_chs as $fa_ch) {
                if ($ch == $fa_ch) {
                    $s++;
                    break;
                }
            }
        }
        if ($s != mb_strlen($value)) {
            $fail('نام باید با حروف فارسی باشد');
        }
    }
}
