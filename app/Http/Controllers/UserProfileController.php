<?php

namespace App\Http\Controllers;

use App\Models\BhaktiSadan;
use App\Models\BloodGroup;
use App\Models\Education;
use App\Models\Language;
use App\Models\Profession;
use App\Models\Seva;
use App\Models\ShikshaLevel;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $educations = Education::all();
        $professions = Profession::all();
        $bloodGroups = BloodGroup::all();
        $bhaktiSadans = BhaktiSadan::all();
        $languages = Language::all();
        $shikshaLevels = ShikshaLevel::all();
        $sevas = Seva::all();

        return view('users.profile', compact('user', 'educations', 'professions', 'bloodGroups', 'bhaktiSadans', 'languages', 'shikshaLevels', 'sevas'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|string|max:255',
            'marriage_anniversary_date' => 'nullable|date',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'mobile_number' => 'required|string|digits:10|unique:users,mobile_number,' . $user->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'education_id' => 'required|exists:education,id',
            'profession_id' => 'required|exists:professions,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'languages' => 'required|array',
            'initiated' => 'required|boolean',
            'spiritual_master_name' => 'required_if:initiated,1|nullable|string|max:255',
            'rounds' => 'required_if:initiated,0|nullable|integer|min:0|max:108',
            'shiksha_levels' => 'nullable|array',
            'second_initiation' => 'required|boolean',
            'bhakti_sadan_id' => 'required|exists:bhakti_sadans,id',
            'life_membership' => 'required|boolean',
            'life_member_no' => 'required_if:life_membership,1|nullable|string|max:255',
            'temple' => 'required_if:life_membership,1|nullable|string|max:255',
            'services' => 'required|array',
        ]);

        $user->update($validatedData);
        $user->languages()->sync($request->languages);
        $user->shikshaLevels()->sync($request->shiksha_levels);
        $user->sevas()->sync($request->services);

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully.');
    }
}
