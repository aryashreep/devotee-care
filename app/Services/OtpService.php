<?php

namespace App\Services;

use App\Models\LoginOtp;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public function generateAndSendOtp(User $user)
    {
        $otp = random_int(100000, 999999);

        LoginOtp::create([
            'mobile_number' => $user->mobile_number,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via Email if the user has one
        if ($user->email) {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        }

        // Send OTP via Interakt WhatsApp API
        if (config('services.interakt.api_key')) {
            Http::withHeaders([
                'Authorization' => 'Basic ' . config('services.interakt.api_key'),
            ])->post(config('services.interakt.api_url'), [
                'phoneNumber' => $user->mobile_number,
                'countryCode' => '+91', // Assuming Indian numbers for now
                'traits' => [
                    'name' => $user->name,
                    'otp' => $otp,
                ],
            ]);
        }
    }

    public function verifyOtp(string $mobileNumber, string $otp): bool
    {
        $loginOtp = LoginOtp::where('mobile_number', $mobileNumber)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->first();

        if ($loginOtp) {
            $loginOtp->delete();
            return true;
        }

        return false;
    }

    public function hasTooManyAttempts(string $mobileNumber): bool
    {
        $count = LoginOtp::where('mobile_number', $mobileNumber)
            ->where('created_at', '>', now()->subMinutes(10))
            ->count();

        return $count >= 3;
    }
}
