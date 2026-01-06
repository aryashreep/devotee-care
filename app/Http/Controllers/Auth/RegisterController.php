<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Education;
use App\Models\Profession;
use App\Models\Language;
use App\Models\ShikshaLevel;
use App\Models\BhaktiSadan;
use App\Models\Seva;
use App\Models\Dependant;
use App\Models\BloodGroup;
use App\Models\State;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showStep1()
    {
        return view('auth.register.step-1');
    }

    public function storeStep1(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'photo' => 'required|image|max:2048',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|in:Single,Married,Divorced',
            'marriage_anniversary_date' => 'nullable|date',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validatedData['photo'] = $path;
        }

        $request->session()->put('step1', $validatedData);

        return redirect()->route('register.step2.show');
    }

    public function showStep2(Request $request)
    {
        if (!$request->session()->has('step1')) {
            return redirect()->route('register.step1.show');
        }
        $states = \App\Models\State::all();
        return view('auth.register.step-2', compact('states'));
    }

    public function storeStep2(Request $request)
    {
        if (!$request->session()->has('step1')) {
            return redirect()->route('register.step1.show');
        }

        $validatedData = $request->validate([
            'email' => 'nullable|email|max:255|unique:users',
            'mobile_number' => 'required|string|digits:10|unique:users',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'pincode' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        if ($this->otpService->hasTooManyAttempts($request->mobile_number)) {
            return back()->withErrors(['mobile_number' => 'Too many OTP requests. Please try again later.']);
        }

        $request->session()->put('step2', $validatedData);

        // This is a temporary user to send the OTP
        $user = new User($validatedData);

        $this->otpService->generateAndSendOtp($user);

        return redirect()->route('register.otp.show');
    }

    public function showOtpForm()
    {
        if (!session('step2')) {
            return Redirect::route('register.step2.show');
        }
        return view('auth.register.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $step2Data = session('step2');
        if (!$step2Data || !isset($step2Data['mobile_number'])) {
            return Redirect::route('register.step2.show')->withErrors(['otp' => 'Something went wrong. Please try again.']);
        }

        if ($this->otpService->verifyOtp($step2Data['mobile_number'], $request->otp)) {
            session()->put('otp_verified', true);
            return redirect()->route('register.step3.show');
        }

        return back()->withErrors(['otp' => 'The provided OTP is invalid or has expired.']);
    }

    public function resendOtp(Request $request)
    {
        $step2Data = session('step2');

        if (!$step2Data || !isset($step2Data['mobile_number'])) {
            return Redirect::route('register.step2.show')->withErrors(['otp' => 'Something went wrong. Please try again.']);
        }

        $user = new User($step2Data);
        $this->otpService->generateAndSendOtp($user);

        return back()->with('success', 'A new OTP has been sent to your mobile number and email.');
    }

    public function showStep3(Request $request)
    {
        if (!$request->session()->has('otp_verified')) {
            return redirect()->route('register.otp.show');
        }
        $educations = Education::all();
        $professions = Profession::all();
        $languages = Language::all();
        $bloodGroups = BloodGroup::all();
        return view('auth.register.step-3', compact('educations', 'professions', 'languages', 'bloodGroups'));
    }

    public function storeStep3(Request $request)
    {
        if (!$request->session()->has('otp_verified')) {
            return redirect()->route('register.otp.show');
        }
        $validatedData = $request->validate([
            'education_id' => 'required|exists:education,id',
            'profession_id' => 'required|exists:professions,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'languages' => 'required|array',
            'languages.*' => 'exists:languages,id',
            'dependants' => 'nullable|array',
            'dependants.*.name' => 'required|string|max:255',
            'dependants.*.age' => 'required|integer|min:0',
            'dependants.*.gender' => 'required|in:Male,Female',
            'dependants.*.dob' => 'required|date',
        ]);
        $request->session()->put('step3', $validatedData);
        return redirect()->route('register.step4.show');
    }

    public function showStep4(Request $request)
    {
        if (!$request->session()->has('step3')) {
            return redirect()->route('register.step3.show');
        }
        $shikshaLevels = ShikshaLevel::all();
        $bhaktiSadans = BhaktiSadan::all();
        $services = Seva::all();
        return view('auth.register.step-4', compact('shikshaLevels', 'bhaktiSadans', 'services'));
    }

    public function storeStep4(Request $request)
    {
        if (!$request->session()->has('step3')) {
            return redirect()->route('register.step3.show');
        }
        $validatedData = $request->validate([
            'initiated' => 'required|boolean',
            'initiated_name' => 'required_if:initiated,1|nullable|string|max:255',
            'spiritual_master' => 'required_if:initiated,1|nullable|string|max:255',
            'rounds' => 'required|integer|min:0|max:108',
            'shiksha_levels' => 'nullable|array',
            'shiksha_levels.*' => 'exists:shiksha_levels,id',
            'second_initiation' => 'required|boolean',
            'bhakti_sadan_id' => 'required|exists:bhakti_sadans,id',
            'life_membership' => 'required|boolean',
            'life_member_no' => 'required_if:life_membership,1|nullable|string|max:255',
            'temple' => 'required_if:life_membership,1|nullable|string|max:255',
            'services' => 'required|array',
            'services.*' => 'exists:sevas,id',
        ]);
        $request->session()->put('step4', $validatedData);
        return redirect()->route('register.step5.show');
    }

    public function showStep5(Request $request)
    {
        if (!$request->session()->has('step4')) {
            return redirect()->route('register.step4.show');
        }
        return view('auth.register.step-5');
    }

    public function storeStep5(Request $request)
    {
        if (!$request->session()->has('step4')) {
            return redirect()->route('register.step4.show');
        }
        $request->validate([
            'disclaimer' => 'required|accepted',
        ]);

        $step1 = $request->session()->get('step1');
        $step2 = $request->session()->get('step2');
        $step3 = $request->session()->get('step3');
        $step4 = $request->session()->get('step4');

        $userData = array_merge($step1, $step2, $step3, $step4);
        $userData['name'] = $userData['full_name'];
        unset($userData['full_name']);

        // Since we are not asking for a password, we can generate a random one.
        $userData['password'] = Hash::make(str()->random(16));

        $user = User::create($userData);

        if (!empty($step3['dependants'])) {
            foreach ($step3['dependants'] as $dependantData) {
                $user->dependants()->create($dependantData);
            }
        }

        $user->languages()->attach($step3['languages']);
        $user->sevas()->attach($step4['services']);
        if (!empty($step4['shiksha_levels'])) {
            $user->shikshaLevels()->attach($step4['shiksha_levels']);
        }

        $request->session()->flush();

        return redirect()->route('login')->with('success', 'Hare Krishna! It is a great pleasure to hear that the account creation was successful. I wish you all the best as you begin your service. Please proceed by logging in with your registered details. 🙏');
    }

    public function autocompleteSpiritualMaster(Request $request)
    {
        $search = $request->get('term');
        $data = User::where('spiritual_master', 'LIKE', '%' . $search . '%')->distinct()->pluck('spiritual_master');
        return response()->json($data);
    }
}
