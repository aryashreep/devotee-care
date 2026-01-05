<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OtpService
{
    public function generateAndSendOtp(User $user)
    {
        $otp = random_int(100000, 999999);

        DB::table('login_otps')->updateOrInsert(
            ['mobile_number' => $user->mobile_number],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        $this->sendWhatsAppOtp($user, $otp);
        $this->sendEmailOtp($user, $otp);

        return $otp;
    }

    protected function sendWhatsAppOtp(User $user, $otp)
    {
        $apiKey = config('services.interakt.api_key');
        $templateName = 'devotee_care_otp';

        // The user's full name and the OTP will be passed as body values
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.interakt.ai/v1/public/track/events/', [
            'phoneNumber' => $user->mobile_number,
            'event' => $templateName,
            'traits' => [
                'name' => $user->name,
                'otp' => $otp,
            ],
        ]);

        // It's good practice to log the response for debugging purposes
        if ($response->failed()) {
            \Illuminate\Support\Facades\Log::error('Interakt API call failed: ' . $response->body());
        }
    }

    protected function sendEmailOtp(User $user, $otp)
    {
        if ($user->email) {
            Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));
        }
    }

    public function verifyOtp($mobileNumber, $otp)
    {
        $otpRecord = DB::table('login_otps')
            ->where('mobile_number', $mobileNumber)
            ->where('otp', $otp)
            ->first();

        if (!$otpRecord) {
            return false;
        }

        if (Carbon::now()->gt($otpRecord->expires_at)) {
            return false; // OTP has expired
        }

        // OTP is valid, so we can delete it to prevent reuse
        DB::table('login_otps')->where('mobile_number', $mobileNumber)->delete();

        return true;
    }
}
