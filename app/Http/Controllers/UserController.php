<?php

namespace App\Http\Controllers;

use App\Models\BhaktiSadan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Education;
use App\Models\Profession;
use App\Models\Language;
use App\Models\BloodGroup;
use App\Models\ShikshaLevel;
use App\Models\Seva;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($perPage);

        return view('users.index', compact('users', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This will now be handled by the multi-step registration
        return redirect()->route('register.step1.show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // This will now be handled by the multi-step registration
        return redirect()->route('register.step1.show');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $educations = Education::all();
        $professions = Profession::all();
        $languages = Language::all();
        $bloodGroups = BloodGroup::all();
        $shikshaLevels = ShikshaLevel::all();
        $bhaktiSadans = BhaktiSadan::all();
        $sevas = Seva::all();

        return view('users.show', compact('user', 'educations', 'professions', 'languages', 'bloodGroups', 'shikshaLevels', 'bhaktiSadans', 'sevas'))->with('editMode', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'mobile_number' => 'required|string|max:15',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|in:Single,Married,Divorced',
            'marriage_anniversary_date' => 'nullable|date',
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
            'spiritual_master' => 'required_if:initiated,1|nullable|string|max:255',
            'rounds' => 'required_if:initiated,0|nullable|integer|min:0|max:16',
            'shiksha_levels' => 'nullable|array',
            'second_initiation' => 'required|boolean',
            'bhakti_sadan_id' => 'required|exists:bhakti_sadans,id',
            'life_membership' => 'required|boolean',
            'life_member_no' => 'required_if:life_membership,1|nullable|string|max:255',
            'temple' => 'required_if:life_membership,1|nullable|string|max:255',
            'services' => 'required|array',
        ]);

        $user->update($request->all());
        $user->languages()->sync($request->languages);
        $user->shikshaLevels()->sync($request->shiksha_levels);
        $user->sevas()->sync($request->services);

        return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Display the authenticated user's profile.
     */
    public function profile()
    {
        $user = auth()->user();
        $educations = Education::all();
        $professions = Profession::all();
        $bloodGroups = BloodGroup::all();
        $bhaktiSadans = BhaktiSadan::all();
        $languages = Language::all();
        $shikshaLevels = ShikshaLevel::all();
        $sevas = Seva::all();

        return view('users.profile', compact('user', 'educations', 'professions', 'bloodGroups', 'bhaktiSadans', 'languages', 'shikshaLevels', 'sevas'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

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
            'education_id' => 'required|exists:educations,id',
            'profession_id' => 'required|exists:professions,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'languages' => 'required|array',
            'initiated' => 'required|boolean',
            'spiritual_master_name' => 'required_if:initiated,1|nullable|string|max:255',
            'rounds' => 'required_if:initiated,0|nullable|integer|min:0|max:16',
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

        return redirect()->route('my-profile.show')->with('success', 'Profile updated successfully.');
    }

    public function toggleEnabled(User $user)
    {
        $user->enabled = !$user->enabled;
        $user->save();

        return response()->json(['success' => true, 'enabled' => $user->enabled]);
    }
}
