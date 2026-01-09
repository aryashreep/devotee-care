<?php

namespace App\Services;

use App\Models\LoginOtp;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public function generateAndSendOtp(User $user): bool
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
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . config('services.interakt.api_key'),
                    'Content-Type' => 'application/json',
                ])->post(config('services.interakt.api_url'), [
                    'countryCode' => '+91',
                    'phoneNumber' => $user->mobile_number,
                    'type' => 'Template',
                    'template' => [
                        'name' => config('services.interakt.template_name'),
                        'languageCode' => config('services.interakt.language_code'),
                        'bodyValues' => [
                            $user->name,
                            (string) $otp,
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    Log::info('WhatsApp OTP sent successfully to ' . $user->mobile_number);
                    return true;
                } else {
                    Log::error('Failed to send WhatsApp OTP to ' . $user->mobile_number . '. Response: ' . $response->body());
                    return false;
                }
            } catch (\Exception $e) {
                Log::error('Exception while sending WhatsApp OTP to ' . $user->mobile_number . '. Error: ' . $e->getMessage());
                return false;
            }
        }

        return true; // If email was sent successfully and WhatsApp is not configured
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
