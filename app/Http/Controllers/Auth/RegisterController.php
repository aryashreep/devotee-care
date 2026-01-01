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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'password' => 'required|string|min:9|confirmed|regex:/^(?=.*[A-Z])(?=.*[0-9]).*$/',
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
        return view('auth.register.step-2');
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
            'city' => 'required|exists:cities,id',
            'state' => 'required|exists:states,id',
            'pincode' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

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
            'spiritual_master_name' => 'required_if:initiated,1|nullable|string|max:255',
            'rounds' => 'required_if:initiated,0|nullable|integer|min:0|max:16',
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

        $userData['password'] = Hash::make($userData['password']);

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
}
