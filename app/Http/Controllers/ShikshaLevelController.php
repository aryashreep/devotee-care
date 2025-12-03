<?php

namespace App\Http\Controllers;

use App\Models\ShikshaLevel;
use Illuminate\Http\Request;

class ShikshaLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shikshaLevels = ShikshaLevel::latest()->paginate(10);
        return view('shiksha-levels.index', compact('shikshaLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shiksha-levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        ShikshaLevel::create($request->all());
        return redirect()->route('shiksha-levels.index')->with('success', 'Shiksha Level created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShikshaLevel $shikshaLevel)
    {
        return view('shiksha-levels.show', compact('shikshaLevel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShikshaLevel $shikshaLevel)
    {
        return view('shiksha-levels.edit', compact('shikshaLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShikshaLevel $shikshaLevel)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $shikshaLevel->update($request->all());
        return redirect()->route('shiksha-levels.index')->with('success', 'Shiksha Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShikshaLevel $shikshaLevel)
    {
        $shikshaLevel->delete();
        return redirect()->route('shiksha-levels.index')->with('success', 'Shiksha Level deleted successfully.');
    }
}
