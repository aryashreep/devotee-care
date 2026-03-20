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
use Illuminate\Support\Facades\Storage;

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
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'mobile_number' => 'required|string|digits:10|unique:users,mobile_number,' . $user->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'education_id' => 'required|exists:education,id',
            'profession_id' => 'required|exists:professions,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'languages' => 'required|array',
            'initiated' => 'required|boolean',
            'initiated_name' => 'required_if:initiated,1|nullable|string|max:255',
            'spiritual_master' => 'required_if:initiated,1|nullable|string|max:255',
            'rounds' => 'required_if:initiated,0|nullable|integer|min:0|max:108',
            'shiksha_levels' => 'nullable|array',
            'second_initiation' => 'required|boolean',
            'bhakti_sadan_id' => 'required|exists:bhakti_sadans,id',
            'life_membership' => 'required|boolean',
            'life_member_no' => 'required_if:life_membership,1|nullable|string|max:255',
            'temple' => 'required_if:life_membership,1|nullable|string|max:255',
            'services' => 'required|array',
            'dependants' => 'nullable|array',
            'dependants.*.id' => 'nullable|exists:dependants,id',
            'dependants.*.name' => 'required|string|max:255',
            'dependants.*.age' => 'required|integer|min:0',
            'dependants.*.gender' => 'required|in:Male,Female',
            'dependants.*.dob' => 'required|date',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($validatedData);
        $user->languages()->sync($request->languages);
        $user->shikshaLevels()->sync($request->shiksha_levels ?? []);
        $user->sevas()->sync($request->services ?? []);

        if ($request->has('dependants')) {
            $dependantIds = [];
            foreach ($request->dependants as $dependantData) {
                if (isset($dependantData['id']) && !empty($dependantData['id'])) {
                    $dependant = \App\Models\Dependant::find($dependantData['id']);
                    if ($dependant && $dependant->user_id === $user->id) {
                        $dependant->update($dependantData);
                        $dependantIds[] = $dependant->id;
                    }
                } else {
                    $dependant = $user->dependants()->create($dependantData);
                    $dependantIds[] = $dependant->id;
                }
            }
            $user->dependants()->whereNotIn('id', $dependantIds)->delete();
        } else {
            $user->dependants()->delete();
        }

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully.');
    }
}
