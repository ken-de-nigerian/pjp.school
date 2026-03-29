<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Coercion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class TeacherLoginController extends Controller
{
    protected int $maxAttempts = 5; // Max login attempts before lockout

    protected int $decayMinutes = 1; // Lockout duration in minutes

    public function showLoginForm(): View
    {
        return view('auth.teacher.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if a user has too many failed login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        // Attempt login first (DO NOT invalidate session yet)
        if ($this->attemptLogin($request)) {
            // Regenerate session only if login is successful
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            return $this->sendLoginResponse();
        }

        // Increment login attempts if failed
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @return array{email: string, password: string}
     */
    protected function credentials(Request $request): array
    {
        return [
            'email' => Coercion::string($request->input('email')),
            'password' => Coercion::string($request->input('password')),
        ];
    }

    /**
     * Attempt to log in with provided credentials.
     */
    protected function attemptLogin(Request $request): bool
    {
        $credentials = $this->credentials($request);

        // Proceed with authentication if the user is not inactive
        return Auth::guard('teacher')->attempt(
            $credentials,
            $request->boolean('remember')
        );
    }

    /**
     * Send a successful login response.
     */
    protected function sendLoginResponse(): RedirectResponse
    {
        return redirect()->intended(route('teacher.dashboard'));
    }

    /**
     * Send a failed login response.
     */
    protected function sendFailedLoginResponse(Request $request): RedirectResponse
    {
        $errorMessage = __('These credentials do not match our records.');

        // Always redirect back with errors
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }

    /**
     * Get the field used for authentication.
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Handle a lockout response when too many logins attempt to occur.
     *
     * @throws ValidationException
     */
    protected function sendLockoutResponse(Request $request): RedirectResponse
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        $errorMessage = trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);

        // Always throw a validation exception with a lockout message
        throw ValidationException::withMessages([
            $this->username() => [$errorMessage],
        ])->status(429);
    }

    /**
     * Clear failed login attempts for the user.
     */
    protected function clearLoginAttempts(Request $request): void
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    /**
     * Get the unique key for tracking login attempts.
     */
    protected function throttleKey(Request $request): string
    {
        return mb_strtolower(Coercion::string($request->input($this->username()))).'|'.Coercion::string($request->ip());
    }

    /**
     * Check if the user has exceeded maximum login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request): bool
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts
        );
    }

    /**
     * Increment the login attempt count.
     */
    protected function incrementLoginAttempts(Request $request): void
    {
        RateLimiter::hit(
            $this->throttleKey($request),
            $this->decayMinutes * 60
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login');
    }
}
