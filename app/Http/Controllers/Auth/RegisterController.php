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
use App\Models\BloodGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
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
            $path = $request->file('photo')->store('temp_photos', 'public');
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

        $challenge = random_int(10, 99);
        $request->session()->put('register_captcha_challenge', $challenge);

        $states = \App\Models\State::all();

        return view('auth.register.step-2', compact('states', 'challenge'));
    }

    public function storeStep2(Request $request)
    {
        if (!$request->session()->has('step1')) {
            return redirect()->route('register.step1.show');
        }

        $validatedData = $request->validate([
            'email' => 'required|email|max:255|unique:users',
            'mobile_number' => ['required', 'regex:/^[6-9][0-9]{9}$/', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(9)->letters()->mixedCase()->numbers()],
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'pincode' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'captcha_answer' => ['required', 'integer'],
            'website_url' => ['nullable', 'size:0'], // honeypot
        ]);

        if ($this->isSuspiciousMobile($validatedData['mobile_number'])) {
            return back()->withErrors(['mobile_number' => 'Please enter a real mobile number.']);
        }

        $expectedCaptcha = (int) $request->session()->pull('register_captcha_challenge', -1);
        if ((int) $request->captcha_answer !== $expectedCaptcha) {
            return back()->withErrors(['captcha_answer' => 'Captcha verification failed. Please try again.']);
        }

        unset($validatedData['captcha_answer'], $validatedData['website_url']);
        $validatedData['password'] = Hash::make($validatedData['password']);

        $request->session()->put('step2', $validatedData);

        return redirect()->route('register.step3.show');
    }

    public function showStep3(Request $request)
    {
        if (!$request->session()->has('step2')) {
            return redirect()->route('register.step2.show');
        }
        $educations = Education::all();
        $professions = Profession::all();
        $languages = Language::all();
        $bloodGroups = BloodGroup::all();
        return view('auth.register.step-3', compact('educations', 'professions', 'languages', 'bloodGroups'));
    }

    public function storeStep3(Request $request)
    {
        if (!$request->session()->has('step2')) {
            return redirect()->route('register.step2.show');
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

        // Move photo from temp to permanent storage
        if (isset($step1['photo']) && \Storage::disk('public')->exists($step1['photo'])) {
            $tempPath = $step1['photo'];
            $fileName = basename($tempPath);
            $newPath = 'photos/' . $fileName;

            // Ensure photos directory exists
            if (!\Storage::disk('public')->exists('photos')) {
                \Storage::disk('public')->makeDirectory('photos');
            }

            \Storage::disk('public')->move($tempPath, $newPath);
            $step1['photo'] = $newPath;
        }

        $userData = array_merge($step1, $step2, $step3, $step4);
        $userData['name'] = $userData['full_name'];
        unset($userData['full_name']);

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

        $devoteeRole = \App\Models\Role::where('name', 'Devotee')->first();
        if ($devoteeRole) {
            $user->roles()->syncWithoutDetaching([$devoteeRole->id]);
        }

        $request->session()->flush();

        return redirect()->route('login')->with('success', 'Registration successful. Please login with your mobile number and password.');
    }

    public function autocompleteSpiritualMaster(Request $request)
    {
        $search = $request->get('term');
        $data = User::where('spiritual_master', 'LIKE', '%' . $search . '%')->distinct()->pluck('spiritual_master');
        return response()->json($data);
    }

    private function isSuspiciousMobile(string $mobileNumber): bool
    {
        if (count(array_unique(str_split($mobileNumber))) === 1) {
            return true;
        }

        return str_contains($mobileNumber, '12345') || str_contains($mobileNumber, '98765');
    }
}
