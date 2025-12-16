<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // GET: /api/profile
    public function getProfile()
    {
        $user_id = Auth::user()->id;
        $profile = User::with('profile', 'interests')->where('id', $user_id)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'age_band'       => 'required',
            'city'            => 'required|string|max:100',
            'dining_budget'  => 'required',
            'has_dogs'        => 'required|boolean',
        ]);

        $profile = UserProfile::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'age_band'      => $request->age_band,
                'city'           => $request->city,
                'dining_budget' => $request->dining_budget,
                'has_dogs'       => $request->has_dogs,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'User profile saved successfully',
            'data'    => $profile
        ]);
    }


    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([

                // Users table (optional)
                'full_name'     => 'nullable|string|max:255',
                'email'         => 'nullable|email|unique:users,email,' . $request->user_id,
                'country'       => 'nullable|string|max:100',
                'country_code'  => 'nullable|string|max:5',
                'phone'         => 'nullable|string|unique:users,phone,' . $request->user_id,

                // User profile table (optional)
                'age_band'      => 'nullable|in:18-24,25-34,35-44,45+',
                'city'           => 'nullable|string|max:100',
                'dining_budget' => 'nullable|in:under_100,100_300,300_500,500_plus',
                'has_dogs'       => 'nullable|boolean',
            ]);

            /** -------------------
             *  Update Users Table
             * ------------------- */
            $userId = Auth::user()->id;
            $user = User::findOrFail($userId);

            $user->update(array_filter([
                'full_name'    => $validated['full_name']    ?? null,
                'email'        => $validated['email']        ?? null,
                'country'      => $validated['country']      ?? null,
                'country_code' => $validated['country_code'] ?? null,
                'phone'        => $validated['phone']        ?? null,
            ]));

            /** -------------------
             *  Update User Profile
             * ------------------- */
            $profile = UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_filter([
                    'age_band'      => $validated['age_band']      ?? null,
                    'city'           => $validated['city']           ?? null,
                    'dining_budget' => $validated['dining_budget'] ?? null,
                    'has_dogs'       => $validated['has_dogs']        ?? null,
                ])
            );

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user'    => $user,
                    'profile' => $profile
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}