<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
        $request->validate(['mobile_number' => 'required|digits:10']);

        if ($this->otpService->hasTooManyAttempts($request->mobile_number)) {
            return back()->withErrors(['mobile_number' => 'Too many OTP requests. Please try again later.']);
        }

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return back()->withErrors(['mobile_number' => 'The provided mobile number does not match our records.']);
        }

        if (!$this->otpService->generateAndSendOtp($user)) {
            return back()->withErrors(['mobile_number' => 'We could not send an OTP to your number. Please try again.']);
        }

        return Redirect::route('login.otp.show')->with('mobile_number', $request->mobile_number);
    }

    public function showOtpForm()
    {
        if (!session('mobile_number')) {
            return Redirect::route('login');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $mobileNumber = session('mobile_number');
        if (!$mobileNumber) {
            return Redirect::route('login')->withErrors(['otp' => 'Something went wrong. Please try again.']);
        }

        if ($this->otpService->verifyOtp($mobileNumber, $request->otp)) {
            $user = User::where('mobile_number', $mobileNumber)->first();
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->hasRole('Devotee')) {
                return redirect()->route('my-profile.show');
            }
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['otp' => 'The provided OTP is invalid or has expired.']);
    }

    public function resendOtp(Request $request)
    {
        $mobileNumber = session('mobile_number');

        if (!$mobileNumber) {
            return Redirect::route('login')->withErrors(['otp' => 'Something went wrong. Please try again.']);
        }

        $user = User::where('mobile_number', $mobileNumber)->first();

        if ($user) {
            if (!$this->otpService->generateAndSendOtp($user)) {
                return back()->withErrors(['otp' => 'We could not send a new OTP to your number. Please try again.']);
            }
            return back()->with('success', 'A new OTP has been sent to your mobile number and email.');
        }

        return Redirect::route('login')->withErrors(['otp' => 'Could not find a user with that mobile number.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
