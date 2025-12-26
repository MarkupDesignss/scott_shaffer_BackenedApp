<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intrest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\URL;

class InterestController extends Controller
{    
    // All interests List
    public function getAllInterests()
    {
    try {
        $interests = Intrest::where('is_active', true)->get()->map(function ($interest) {
            return [
                'id'         => $interest->id,
                'name'       => $interest->name,
                'icon'       => $interest->icon 
                    ? url('storage/' . $interest->icon)
                    : null,
                'is_active'  => $interest->is_active,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $interests
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch interests',
            'reason'  => $th->getMessage()
        ], 500);
    }
}

    //Add User Interests
    public function addUserInterests(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'     => 'required|exists:users,id',
                'interests'   => 'required|array|min:3',
                'interests.*' => 'exists:interests,id',
            ]);

            $user = User::find($validated['user_id']);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            // Replace old interests safely
            $user->interests()->sync($validated['interests']);
            User::where('id', $user->id)->update([
                'is_interest_completed' => true
            ]);

            return response()->json([
                'success'            => true,
                'message'            => 'Interests saved successfully',
                'user_id'            => $user->id,
                'Data'               => $user->interests()->pluck('interests.id'),
            ], 200);
        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to save interests',
                'reason'  => $th->getMessage()
            ], 500);
        }
    }

    // Get Authenticated User Interests
    public function getUserInterests(Request $request)
    {
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired authentication token',
            ], 401);
        }

        $data = $user->interests()->get()->map(function ($interest) {
            return [
                'id'        => $interest->id,
                'name'      => $interest->name,
                'icon'      => $interest->icon
                    ? url('storage/' . $interest->icon)
                    : null,
                'is_active' => $interest->is_active,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch user interests',
            'reason'  => $th->getMessage()
        ], 500);
    }
}

}