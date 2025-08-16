<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail("فیلد {$attribute} باید یک رشته معتبر باشد.");
            return;
        }
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])(?!.*(.)\1{2,}).+$/';
        if (!preg_match($pattern, $value)) {
            $fail("رمز عبور باید شامل حروف بزرگ و کوچک، عدد، کاراکتر خاص باشد و هیچ کاراکتری بیش از دو بار پشت سر هم تکرار نشود.");
        }
    }
}
