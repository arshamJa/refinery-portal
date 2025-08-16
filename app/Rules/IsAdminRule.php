<?php

namespace App\Rules;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class IsAdminRule implements ValidationRule
{
    protected $password;

    public function __construct($password)
    {
        $this->password = $password;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $users = User::where('p_code', $value)->get();
        $valid = false;
        foreach ($users as $user) {
            if (Hash::check($this->password, $user->password) && $user->hasRole(UserRole::ADMIN->value)) {
                $valid = true;
                break;
            }
        }
        if (!$valid) {
            $fail('کد کاربری باید متعلق به یک کاربر با نقش ادمین باشد.');
        }
    }
}
