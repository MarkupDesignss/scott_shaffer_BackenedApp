<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // GET: /api/profile
    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => $user
        ]);
    }

    // PUT: /api/profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name'    => 'nullable|string|max:255',
            'email'        => 'nullable|email|unique:users,email,' . $request->user()->id,
            'country'      => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:5',
            'phone'        => 'nullable|string|unique:users,phone,' . $request->user()->id,
        ]);

        $user = $request->user();

        $user->update($request->only([
            'full_name',
            'email',
            'country',
            'country_code',
            'phone'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => $user
        ]);
    }

    /**
     * Update or create user profile (Progressive Profiling Step).
    */

   public function update(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'user_id'        => 'required|exists:users,id',
                'age_band'       => 'nullable|string',
                'city'           => 'nullable|string',
                'dining_budget'  => 'nullable|string',
                'has_dogs'       => 'boolean',
            ]);

            $userId = $validated['user_id'];

            // Check if profile exists
            $profile = UserProfile::firstOrCreate(
                ['user_id' => $userId],
                [] // defaults, empty for now
            );

            // Update with new data
            $profile->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile created/updated successfully',
                'data'    => $profile
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Any other error
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


}