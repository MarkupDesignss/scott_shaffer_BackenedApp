<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intrest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InterestController extends Controller
{
    /**
     * 1. Get all active interests
     */
    public function getAllInterests()
    {
        try {
            $interests = Intrest::where('is_active', true)->get();

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

    /**
     * 2. Save selected user interests (minimum 3)
     */
    public function saveUserInterests(Request $request)
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
                'selected_interests' => $user->interests()->pluck('interests.id'),
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

    /**
     * 3. Get logged-in user's selected interests
     */
    public function getUserInterests(Request $request, $user_id)
    {
        try {

            $user = User::find($user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            $data = $user->interests()->get();

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
