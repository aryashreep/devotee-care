<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getStates()
    {
        $states = State::all();
        return response()->json($states);
    }

    public function getCities(State $state)
    {
        $cities = $state->cities;
        return response()->json($cities);
    }
}
