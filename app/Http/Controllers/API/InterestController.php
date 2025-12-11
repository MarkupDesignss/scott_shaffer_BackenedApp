<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interest;
use App\Models\Intrest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InterestController extends Controller
{
    // 1. Get all interests
    public function getAllInterests()
    {
        $interests = Intrest::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $interests
        ]);
    }


    // 2. Save selected user interests (minimum 3)
    public function saveUserInterests(Request $request)
    {
        $request->validate([
            'interests'   => 'required|array|min:3',
            'interests.*' => 'exists:interests,id',
        ]);

        $user = $request->user();

        // Save user interests (replace old ones)
        $user->interests()->sync($request->interests);

        return response()->json([
            'success' => true,
            'message' => 'Interests saved successfully.',
            'selected_interests' => $request->interests
        ]);
    }


    // 3. Get logged-in user's selected interests
    public function getUserInterests(Request $request)
    {
        $user = Auth::user();

        $data = $user->interests()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
