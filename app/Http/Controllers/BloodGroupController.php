<?php

namespace App\Http\Controllers;

use App\Models\BloodGroup;
use Illuminate\Http\Request;

class BloodGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloodGroups = BloodGroup::latest()->paginate(10);
        return view('blood-groups.index', compact('bloodGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blood-groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        BloodGroup::create($request->all());
        return redirect()->route('blood-groups.index')->with('success', 'Blood Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodGroup $bloodGroup)
    {
        return view('blood-groups.show', compact('bloodGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodGroup $bloodGroup)
    {
        return view('blood-groups.edit', compact('bloodGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodGroup $bloodGroup)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $bloodGroup->update($request->all());
        return redirect()->route('blood-groups.index')->with('success', 'Blood Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodGroup $bloodGroup)
    {
        $bloodGroup->delete();
        return redirect()->route('blood-groups.index')->with('success', 'Blood Group deleted successfully.');
    }
}
