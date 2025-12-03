<?php

namespace App\Http\Controllers;

use App\Models\BhaktiSadan;
use App\Models\User;
use Illuminate\Http\Request;

class BhaktiSadanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bhaktiSadans = BhaktiSadan::with('leader')->latest()->paginate(10);
        return view('bhakti-sadans.index', compact('bhaktiSadans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('bhakti-sadans.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:users,id',
        ]);
        BhaktiSadan::create($request->all());
        return redirect()->route('bhakti-sadans.index')->with('success', 'Bhakti Sadan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BhaktiSadan $bhaktiSadan)
    {
        return view('bhakti-sadans.show', compact('bhaktiSadan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BhaktiSadan $bhaktiSadan)
    {
        $users = User::all();
        return view('bhakti-sadans.edit', compact('bhaktiSadan', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BhaktiSadan $bhaktiSadan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:users,id',
        ]);
        $bhaktiSadan->update($request->all());
        return redirect()->route('bhakti-sadans.index')->with('success', 'Bhakti Sadan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BhaktiSadan $bhaktiSadan)
    {
        $bhaktiSadan->delete();
        return redirect()->route('bhakti-sadans.index')->with('success', 'Bhakti Sadan deleted successfully.');
    }
}
