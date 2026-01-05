<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;
use App\Models\User;

class LoginController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function requestOtp(Request $request)
    {
        $credentials = $request->validate([
            'mobile_number' => ['required', 'digits:10'],
        ]);

        $user = User::where('mobile_number', $credentials['mobile_number'])->first();

        if (!$user) {
            return back()->withErrors([
                'mobile_number' => 'The provided mobile number does not match our records.',
            ]);
        }

        if (!$user->enabled) {
            return back()->withErrors([
                'mobile_number' => 'Your account is disabled. Please contact an administrator.',
            ]);
        }

        $this->otpService->generateAndSendOtp($user);

        $request->session()->put('mobile_number', $user->mobile_number);

        return redirect()->route('login.otp.show');
    }

    public function showOtpForm()
    {
        return view('auth.login-otp');
    }

    public function resendOtp(Request $request)
    {
        $mobileNumber = $request->session()->get('mobile_number');

        if (!$mobileNumber) {
            return redirect()->route('login')->withErrors([
                'mobile_number' => 'Your session has expired. Please try again.',
            ]);
        }

        $user = User::where('mobile_number', $mobileNumber)->first();

        if ($user) {
            $this->otpService->generateAndSendOtp($user);
            return back()->with('success', 'A new OTP has been sent to your mobile number and email.');
        }

        return redirect()->route('login')->withErrors([
            'mobile_number' => 'An unexpected error occurred. Please try again.',
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        $credentials = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $mobileNumber = $request->session()->get('mobile_number');

        if (!$mobileNumber) {
            return redirect()->route('login')->withErrors([
                'mobile_number' => 'Your session has expired. Please try again.',
            ]);
        }

        if ($this->otpService->verifyOtp($mobileNumber, $credentials['otp'])) {
            $user = User::where('mobile_number', $mobileNumber)->first();
            Auth::login($user);
            $request->session()->regenerate();

            $request->session()->forget('mobile_number');

            if ($user->hasRole('Devotee')) {
                return redirect()->route('my-profile.show');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'otp' => 'The provided OTP is invalid or has expired.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
