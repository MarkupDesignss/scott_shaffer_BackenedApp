<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * GET: /api/profile
     * User + Profile + Interests + Categories + Items
     */
    public function getProfile()
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = User::with([
                'profile:id,user_id,age_band,city,dining_budget,has_dogs,profile_image',
                'consent:id,user_id,accepted_terms_privacy,campaign_marketing,accepted_at',
                'interests:id,name'
            ])
                ->select('id', 'full_name', 'email', 'phone', 'country', 'country_code')
                ->find($userId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            /** 🔥 Convert profile_image to full URL */
            if ($user->profile && $user->profile->profile_image) {
                $user->profile->profile_image = asset($user->profile->profile_image);
            } else {
                $user->profile->profile_image = null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => [
                    'user' => $user
                ]
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /**
     * POST: /api/profile/store
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id'        => 'required|exists:users,id',
                'age_band'       => 'nullable',
                'city'           => 'nullable|string|max:100',
                'dining_budget'  => 'nullable',
                'has_dogs'       => 'nullable|boolean',
            ]);

            $profile = UserProfile::updateOrCreate(
                ['user_id' => $validated['user_id']],
                $validated
            );
            User::where('id', $validated['user_id'])->update([
                'is_profile_completed' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile saved successfully',
                'data'    => $profile
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to save profile',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT: /api/profile/update
     */


    public function updateProfile(Request $request)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $validated = $request->validate([
                'country'       => 'nullable|string|max:100',
                'country_code'  => 'nullable|string|max:5',
                'phone'         => 'nullable|string|unique:users,phone,' . $userId,

                'age_band'      => 'nullable|string',
                'city'          => 'nullable|string|max:150',
                'dining_budget' => 'nullable|string|max:100',
                'has_dogs'      => 'nullable|boolean',

                'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'interests'     => 'nullable|array',
                'interests.*'   => 'exists:interests,id',
            ]);

            /** -----------------------
             * Update User
             * ---------------------- */
            $user = User::findOrFail($userId);
            $user->update(array_filter([
                'country'      => $validated['country'] ?? null,
                'country_code' => $validated['country_code'] ?? null,
                'phone'        => $validated['phone'] ?? null,
            ]));

            /** -----------------------
             * Profile Image Upload (FIXED)
             * ---------------------- */
            $profile = UserProfile::firstOrCreate(['user_id' => $userId]);

            if ($request->hasFile('profile_image')) {

                $image      = $request->file('profile_image');
                $fileName   = uniqid() . '.' . $image->getClientOriginalExtension();
                $uploadPath = public_path('storage/profile-images');

                // create directory if not exists
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // delete old image
                if ($profile->profile_image) {
                    $oldPath = public_path($profile->profile_image);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                // move image
                $image->move($uploadPath, $fileName);

                // save relative path in DB
                $profile->profile_image = 'storage/profile-images/' . $fileName;
            }

            /** -----------------------
             * Update Profile
             * ---------------------- */
            $profile->fill(array_filter([
                'age_band'      => $validated['age_band'] ?? null,
                'city'          => $validated['city'] ?? null,
                'dining_budget' => $validated['dining_budget'] ?? null,
                'has_dogs'      => $validated['has_dogs'] ?? null,
            ]));

            $profile->save();

            /** -----------------------
             * Sync Interests
             * ---------------------- */
            if (isset($validated['interests'])) {
                $user->interests()->sync($validated['interests']);
            }

            /** -----------------------
             * Response
             * ---------------------- */
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'profile_image' => $profile->profile_image
                        ? asset($profile->profile_image)
                        : null,
                ]
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
