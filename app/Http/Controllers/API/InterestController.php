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
            'user_id'     => 'required|exists:users,id',
            'interests'   => 'required|array|min:3',
            'interests.*' => 'exists:interests,id',
        ]);

        $user = User::find($request->user_id);
        // Replace old interests (safe for re-submit)
        $user->interests()->sync($request->interests);

        return response()->json([
            'success' => true,
            'message' => 'Interests saved successfully',
            'user_id' => $user->id,
            'selected_interests' => $user->interests()->pluck('interests.id'),
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
