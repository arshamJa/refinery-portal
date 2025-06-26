<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
        // Default rules
        $rules = [
            'p_code' => ['required', 'numeric', 'digits:6', 'exists:users,p_code'],
            'password' => ['required',
                \Illuminate\Validation\Rules\Password::min(6)->max(8)->letters()->numbers()],
            'remember' => ['boolean'],
        ];

        if ($this->isSuperAdmin()) {
            $rules['password'] = ['required', 'string','max:30'];
            $rules['p_code'] = ['required', 'string','min:6','max:30'];
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

        if (!Auth::attempt($this->only(['p_code', 'password']), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'p_code' => __('auth.failed'),
            ]);
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
