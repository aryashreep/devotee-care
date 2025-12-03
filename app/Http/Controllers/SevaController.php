<?php

namespace App\Http\Controllers;

use App\Models\Seva;
use Illuminate\Http\Request;

class SevaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sevas = Seva::latest()->paginate(10);
        return view('sevas.index', compact('sevas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sevas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Seva::create($request->all());
        return redirect()->route('sevas.index')->with('success', 'Seva created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Seva $seva)
    {
        return view('sevas.show', compact('seva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seva $seva)
    {
        return view('sevas.edit', compact('seva'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seva $seva)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $seva->update($request->all());
        return redirect()->route('sevas.index')->with('success', 'Seva updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seva $seva)
    {
        $seva->delete();
        return redirect()->route('sevas.index')->with('success', 'Seva deleted successfully.');
    }
}
