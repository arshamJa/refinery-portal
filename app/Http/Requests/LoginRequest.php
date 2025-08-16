<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use App\Rules\AdminRule;
use App\Rules\IsAdminRule;
use App\Rules\PasswordRule;
use App\Rules\SuperAdminRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $p_code = $this->input('p_code');
        $password = $this->input('password');
        // Default rules
        $rules = [
            'p_code' => ['required', 'numeric', 'digits:6', 'exists:users,p_code'],
            'password' => ['required',
                \Illuminate\Validation\Rules\Password::min(6)->max(10)->letters()->numbers()],
            'remember' => ['boolean'],
        ];
        // Super Admin rules (override defaults)
        if ($this->isSuperAdmin()) {
            $rules['p_code'] = ['required', 'string', 'in:Samael'];
            $rules['password'] = ['required', 'string', 'min:6', 'max:30', new PasswordRule()];
        }
        // Admin rules (override defaults)
        if ($p_code === 'admin') {
            $rules['p_code'] = ['required', 'string', new IsAdminRule($password)];
            $rules['password'] = ['required', 'string','min:6', 'max:30', new PasswordRule()];
        }
        return $rules;
    }
    protected function isSuperAdmin(): bool
    {
        $user = User::where('p_code', $this->input('p_code'))->first();
        return $user && $user->hasRole(UserRole::SUPER_ADMIN->value);
    }
    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $p_code = $this->input('p_code');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // Super Admin
        if ($this->isSuperAdmin()) {
            $user = User::where('p_code', $p_code)->first();
            Auth::login($user, $remember);
        }
        // Admin (multiple users with same p_code)
        elseif ($p_code === 'admin') {
            $users = User::where('p_code', 'admin')->get();
            $user = $users->first(fn($u) => Hash::check($password, $u->password) && $u->hasRole(UserRole::ADMIN->value));
            if (!$user) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'p_code' => __('auth.failed'),
                ]);
            }
            Auth::login($user, $remember);
        }
        // Normal users
        else {
            $user = User::where('p_code', $p_code)->first();
            if (!$user || !Hash::check($password, $user->password)) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'p_code' => __('auth.failed'),
                ]);
            }
            Auth::login($user, $remember);
        }
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'p_code' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('p_code')).'|'.$this->ip());
    }

}
