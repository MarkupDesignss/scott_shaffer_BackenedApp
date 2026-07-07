<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\ListModel;
use App\Models\ListItem;
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

        /** -----------------------
         *  Fetch User with Relations
         * ---------------------- */
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


  
    // public function updateProfile(Request $request)
    // {
    //     try {
    //         $userId = Auth::id();

    //         if (!$userId) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized'
    //             ], 401);
    //         }

    //         $validated = $request->validate([
    //             'country'       => 'nullable|string|max:100',
    //             'country_code'  => 'nullable|string|max:5',
    //             'phone'         => 'nullable|string|unique:users,phone,' . $userId,

    //             'age_band'      => 'nullable|string',
    //             'city'          => 'nullable|string|max:150',
    //             'dining_budget' => 'nullable|string|max:100',
    //             'has_dogs'      => 'nullable',

    //             'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    //             'interests'     => 'nullable|array',
    //             'interests.*'   => 'exists:interests,id',
    //         ]);

    //         /** -----------------------
    //          * Update User
    //          * ---------------------- */
    //         $user = User::findOrFail($userId);
    //         $user->update(array_filter([
    //             'country'      => $validated['country'] ?? null,
    //             'country_code' => $validated['country_code'] ?? null,
    //             'phone'        => $validated['phone'] ?? null,
    //         ]));

    //         /** -----------------------
    //          * Profile Image Upload (FIXED)
    //          * ---------------------- */
    //         $profile = UserProfile::firstOrCreate(['user_id' => $userId]);

    //         if ($request->hasFile('profile_image')) {

    //             $image      = $request->file('profile_image');
    //             $fileName   = uniqid() . '.' . $image->getClientOriginalExtension();
    //             $uploadPath = public_path('storage/profile-images');

    //             // create directory if not exists
    //             if (!File::exists($uploadPath)) {
    //                 File::makeDirectory($uploadPath, 0755, true);
    //             }

    //             // delete old image
    //             if ($profile->profile_image) {
    //                 $oldPath = public_path($profile->profile_image);
    //                 if (File::exists($oldPath)) {
    //                     File::delete($oldPath);
    //                 }
    //             }

    //             // move image
    //             $image->move($uploadPath, $fileName);

    //             // save relative path in DB
    //             $profile->profile_image = 'storage/profile-images/' . $fileName;
    //         }else {
    //             if ($profile->profile_image) {
    //                 $oldPath = public_path($profile->profile_image);
    //                 if (File::exists($oldPath)) {
    //                     File::delete($oldPath);
    //                 }
    //             }

    //             $profile->profile_image = null;
    //         }

    //         /** -----------------------
    //          * Update Profile
    //          * ---------------------- */
    //         $profile->fill(array_filter([
    //             'age_band'      => $validated['age_band'] ?? null,
    //             'city'          => $validated['city'] ?? null,
    //             'dining_budget' => $validated['dining_budget'] ?? null,
    //             'has_dogs'      => $validated['has_dogs'] ?? null,
    //         ]));

    //         $profile->save();

    //         /** -----------------------
    //          * Sync Interests
    //          * ---------------------- */
    //         if (isset($validated['interests'])) {
    //             $user->interests()->sync($validated['interests']);
    //         }

    //         /** -----------------------
    //          * Response
    //          * ---------------------- */
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Profile updated successfully',
    //             'data' => [
    //                 'profile_image' => $profile->profile_image
    //                     ? asset($profile->profile_image)
    //                     : null,
    //                 'user' => $user
    //             ]
    //         ], 200);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Profile update failed',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    public function removeProfileImage(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
            ], 401);
        }

        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'code' => 404,
                'message' => 'Profile not found',
            ], 404);
        }

        // Delete old image from storage
        if ($profile->profile_image && File::exists(public_path($profile->profile_image))) {
            File::delete(public_path($profile->profile_image));
        }

        // Set null in DB
        $profile->profile_image = null;
        $profile->save();

        return response()->json([
            'code' => 200,
            'message' => 'Profile image removed successfully',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'code' => 500,
            'message' => 'Internal server error',
            'error' => $e->getMessage()
        ], 500);
    }
}
    
    // public function updateProfile(Request $request)
    // {
    //     try {
    //         $userId = Auth::id();
    
    //         if (!$userId) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Unauthorized'
    //             ], 401);
    //         }
    //         $validated = $request->validate([
    //             'country'       => 'nullable|string|max:100',
    //             'country_code'  => 'nullable|string|max:5',
    //             'phone'         => 'nullable|string|unique:users,phone,' . $userId,
    
    //             'age_band'      => 'nullable|string',
    //             'city'          => 'nullable|string|max:150',
    //             'dining_budget' => 'nullable|string|max:100',
    //             'has_dogs'      => 'nullable',
    
    //             'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
    //             'interests'     => 'nullable|array',
    //             'interests.*'   => 'exists:interests,id',
    //         ]);
    
    //         /** -----------------------
    //          * Update User
    //          * ---------------------- */
    //         $user = User::findOrFail($userId);
    //         $user->update(array_filter([
    //             'country'      => $validated['country'] ?? null,
    //             'country_code' => $validated['country_code'] ?? null,
    //             'phone'        => $validated['phone'] ?? null,
    //         ]));
    
    //         /** -----------------------
    //          * Profile Instance
    //          * ---------------------- */
    //         $profile = UserProfile::firstOrCreate(['user_id' => $userId]);
    
    //         /** -----------------------
    //          * Profile Image Logic (UPDATED)
    //          * ---------------------- */
    
    //         // Case 1: New image upload
    //         if ($request->hasFile('profile_image')) {
    
    //             $image      = $request->file('profile_image');
    //             $fileName   = uniqid() . '.' . $image->getClientOriginalExtension();
    //             $uploadPath = public_path('storage/profile-images');
    
    //             if (!File::exists($uploadPath)) {
    //                 File::makeDirectory($uploadPath, 0755, true);
    //             }
    
    //             // delete old image
    //             if ($profile->profile_image) {
    //                 $oldPath = public_path($profile->profile_image);
    //                 if (File::exists($oldPath)) {
    //                     File::delete($oldPath);
    //                 }
    //             }
    
    //             $image->move($uploadPath, $fileName);
    
    //             $profile->profile_image = 'storage/profile-images/' . $fileName;
    //         }
    
    //         //  Case 3: key present but empty → remove image
    //         elseif ($request->has('profile_image')) {
    
    //             if ($profile->profile_image) {
    //                 $oldPath = public_path($profile->profile_image);
    //                 if (File::exists($oldPath)) {
    //                     File::delete($oldPath);
    //                 }
    //             }
    
    //             $profile->profile_image = null;
    //         }
    
    //         // Case 2: key not present → DO NOTHING (old image stays)
    
    //         /** -----------------------
    //          * Update Profile
    //          * ---------------------- */
    //         $profile->fill(array_filter([
    //             'age_band'      => $validated['age_band'] ?? null,
    //             'city'          => $validated['city'] ?? null,
    //             'dining_budget' => $validated['dining_budget'] ?? null,
    //             'has_dogs'      => $validated['has_dogs'] ?? null,
    //         ], function ($value) {
    //             return !is_null($value);
    //         }));
            
    //         $profile->save();
            
    //         /** -----------------------
    //          * Sync Interests
    //          * ---------------------- */
    //         if (isset($validated['interests'])) {
    //             $user->interests()->sync($validated['interests']);
    //         }
    
    //         /** -----------------------
    //          * Response
    //          * ---------------------- */
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Profile updated successfully',
    //             'data' => [
    //                 'profile_image' => $profile->profile_image
    //                     ? asset($profile->profile_image)
    //                     : null,
    //                 'user' => $user
    //             ]
    //         ], 200);
    
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Profile update failed',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
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
                // 'phone'         => 'nullable|string|unique:users,phone,' . $userId,
    
                'phone'      => 'nullable|string',
                'age_band'      => 'nullable|string',
                'city'          => 'nullable|string|max:150',
                'dining_budget' => 'nullable|string|max:100',
                'has_dogs'      => 'nullable',
    
                'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
    
                'interests'     => 'nullable|array',
                'interests.*'   => 'exists:interests,id',
            ]);
    
            /**
             * ------------------------------------
             * User
             * ------------------------------------
             */
            $user = User::findOrFail($userId);
    
            $user->update(array_filter([
                'country'      => $validated['country'] ?? null,
                'country_code' => $validated['country_code'] ?? null,
                'phone'        => $validated['phone'] ?? null,
            ], function ($value) {
                return !is_null($value);
            }));
    
            /**
             * ------------------------------------
             * Profile
             * ------------------------------------
             */
            $profile = UserProfile::firstOrCreate([
                'user_id' => $userId
            ]);
    
            /**
             * ------------------------------------
             * Profile Image
             * ------------------------------------
             */
    
            // Upload new image
            if ($request->hasFile('profile_image')) {
    
                $image = $request->file('profile_image');
    
                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
    
                $uploadPath = public_path('storage/profile-images');
    
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
    
                // Delete old image
                if ($profile->profile_image) {
    
                    $oldPath = public_path($profile->profile_image);
    
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
    
                $image->move($uploadPath, $fileName);
    
                $profile->profile_image = 'storage/profile-images/' . $fileName;
            }
    
            // Remove image when key is sent empty
            elseif ($request->has('profile_image')) {
    
                if ($profile->profile_image) {
    
                    $oldPath = public_path($profile->profile_image);
    
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
    
                $profile->profile_image = null;
            }
    
            /**
             * ------------------------------------
             * Update Profile Data
             * ------------------------------------
             */
            $profile->fill(array_filter([
                'age_band'      => $validated['age_band'] ?? null,
                'city'          => $validated['city'] ?? null,
                'dining_budget' => $validated['dining_budget'] ?? null,
                'has_dogs'      => $validated['has_dogs'] ?? null,
            ], function ($value) {
                return !is_null($value);
            }));
    
            $profile->save();
    
            /**
             * ------------------------------------
             * Interests
             * Keep old interests and add new ones
             * ------------------------------------
             */
            if (!empty($validated['interests'])) {
    
                $user->interests()->syncWithoutDetaching(
                    $validated['interests']
                );
            }
    
            /**
             * ------------------------------------
             * Refresh Relations
             * ------------------------------------
             */
            $user->load([
                'interests',
                'profile'
            ]);
    
            /**
             * ------------------------------------
             * Response
             * ------------------------------------
             */
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'profile_image' => $profile->profile_image
                        ? asset($profile->profile_image)
                        : null,
    
                    'user' => $user,
    
                    'interests' => $user->interests->map(function ($interest) {
                        return [
                            'id'   => $interest->id,
                            'name' => $interest->name,
                        ];
                    })->values(),
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
    
    public function removeInterest(Request $request)
    {
        try {
    
            $user = Auth::user();
    
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
    
            $validated = $request->validate([
                'interest_ids'   => 'required|array|min:1',
                'interest_ids.*' => 'exists:interests,id',
            ]);
    
            $interestIds = collect($validated['interest_ids'])
                ->unique()
                ->values()
                ->toArray();
    
            // User's current interests
            $currentInterestIds = $user->interests()
                ->pluck('interests.id')
                ->toArray();
    
            // Interests that actually belong to user
            $userInterestIdsToRemove = array_intersect(
                $currentInterestIds,
                $interestIds
            );
    
            if (empty($userInterestIdsToRemove)) {
                return response()->json([
                    'success' => false,
                    'message' => 'None of the provided interests belong to the user.'
                ], 404);
            }
    
            $currentCount = count($currentInterestIds);
            $removeCount  = count($userInterestIdsToRemove);
            $remaining    = $currentCount - $removeCount;
    
            if ($remaining < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must have at least 3 interests.',
                    'current_interests' => $currentCount,
                    'attempting_to_remove' => $removeCount,
                    'remaining_after_removal' => $remaining
                ], 422);
            }
    
            $user->interests()->detach($userInterestIdsToRemove);
    
            return response()->json([
                'success' => true,
                'message' => 'Interests removed successfully.',
                'data' => [
                    'removed_interest_ids' => array_values($userInterestIdsToRemove),
                    'remaining_interests_count' => $user->interests()->count(),
                    'remaining_interest_ids' => $user->interests()
                        ->pluck('interests.id')
                        ->toArray(),
                ]
            ]);
    
        } catch (\Throwable $e) {
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove interests.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    


public function deleteAccount(Request $request)
{
    DB::beginTransaction();

    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $userId = $user->id;

        /** -----------------------
         * Delete Profile Image (if exists)
         * ---------------------- */
        $profile = UserProfile::where('user_id', $userId)->first();

        if ($profile && $profile->profile_image) {
            $path = public_path($profile->profile_image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        /** -----------------------
         * Delete List Items
         * ---------------------- */
        $listIds = ListModel::where('user_id', $userId)->pluck('id');

        ListItem::whereIn('list_id', $listIds)->delete();

        /** -----------------------
         * Delete Lists
         * ---------------------- */
        ListModel::where('user_id', $userId)->delete();

        /** -----------------------
         * Delete User Interests
         * ---------------------- */
        DB::table('user_interest')->where('user_id', $userId)->delete();

        /** -----------------------
         * Delete User Profile
         * ---------------------- */
        UserProfile::where('user_id', $userId)->delete();

        /** -----------------------
         * Delete User
         * ---------------------- */
        $user->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'User account deleted successfully'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Account deletion failed',
            'error'   => $e->getMessage()
        ], 500);
    }
}

}
