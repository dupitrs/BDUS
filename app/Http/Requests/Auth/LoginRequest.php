<?php

namespace App\Http\Requests\Auth;

use App\Models\User; // <- Pievieno šo!
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        if (!$this->attemptLogin($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    protected function attemptLogin(array $credentials, bool $remember): bool
    {
        // Mēģina kā lietotājs
        $lietotajs = User::where('lietotaja_epasts', $credentials['email'])->first();
    
        if ($lietotajs && Hash::check($credentials['password'], $lietotajs->parole)) {
            Auth::login($lietotajs, $remember);
            session(['user_type' => 'lietotajs']);
            return true;
        }
    
        // Mēģina kā administrators
        $adminModel = \App\Models\Admin::where('administratora_epasts', $credentials['email'])->first();

        if ($adminModel && Hash::check($credentials['password'], $adminModel->parole)) {
            Auth::guard('admin')->login($adminModel, $remember);
            session(['user_type' => 'admin']);
            return true;
        }

        
        return false;
    }
    


    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
