<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $challenge = random_int(10, 99);
        $request->session()->put('login_captcha_challenge', $challenge);

        return view('auth.login', ['captchaChallenge' => $challenge]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'password' => ['required', 'string'],
            'captcha_answer' => ['required', 'integer'],
            'company_name' => ['nullable', 'size:0'], // honeypot
        ]);

        if ($this->isSuspiciousMobile($request->mobile_number)) {
            throw ValidationException::withMessages([
                'mobile_number' => 'Please enter a valid mobile number.',
            ]);
        }

        $expectedCaptcha = (int) $request->session()->pull('login_captcha_challenge', -1);
        if ((int) $request->captcha_answer !== $expectedCaptcha) {
            throw ValidationException::withMessages([
                'captcha_answer' => 'Captcha verification failed. Please try again.',
            ]);
        }

        if (!Auth::attempt($request->only('mobile_number', 'password'), true)) {
            throw ValidationException::withMessages([
                'mobile_number' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('Devotee')) {
            return redirect()->route('my-profile.show');
        }

        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function isSuspiciousMobile(string $mobileNumber): bool
    {
        if (count(array_unique(str_split($mobileNumber))) === 1) {
            return true;
        }

        return str_contains($mobileNumber, '12345') || str_contains($mobileNumber, '98765');
    }
}
