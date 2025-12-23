<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
             *  User Basic Info
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

            // /** -----------------------
            //  *  Interest â†’ Category â†’ Items
            //  * ---------------------- */
            // $interestsData = [];

            // foreach ($user->interests as $interest) {

            //     $categories = DB::table('catalog_categories')
            //         ->where('interest_id', $interest->id)
            //         ->where('status', 1)
            //         ->select('id', 'name', 'slug', 'icon', 'color')
            //         ->get()
            //         ->map(function ($category) {

            //             $items = DB::table('catalog_items')
            //                 ->where('category_id', $category->id)
            //                 ->where('status', 1)
            //                 ->whereNull('deleted_at')
            //                 ->select('id', 'name', 'description', 'image_url')
            //                 ->get();

            //             $category->items = $items;
            //             return $category;
            //         });

            //     $interestsData[] = [
            //         'id'         => $interest->id,
            //         'name'       => $interest->name,
            //         'categories' => $categories
            //     ];
            // }

            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => [
                    'user'      => $user,
                    //    'interests' => $interestsData
                ]
            ]);
        } catch (\Exception $e) {
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
                'age_band'       => 'required',
                'city'           => 'required|string|max:100',
                'dining_budget'  => 'required',
                'has_dogs'       => 'required|boolean',
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
            /** -----------------------
             *  Validation
             * ---------------------- */
            $validated = $request->validate([
                'country'       => 'nullable|string|max:100',
                'country_code'  => 'nullable|string|max:5',
                'phone'         => 'nullable|string|unique:users,phone,' . $userId,

                'age_band'      => 'nullable|string',
                'city'          => 'nullable|string|max:150',
                'dining_budget' => 'nullable|string|max:100',
                'has_dogs'      => 'nullable',

                // 🔥 Profile Image
                'profile_image' => 'nullable|image|max:2048',

                // 🔥 Interests
                'interests'     => 'nullable|array',
                'interests.*'   => 'exists:interests,id',
            ]);

            /** -----------------------
             *  Update User
             * ---------------------- */
            $user = User::findOrFail($userId);

            $user->update(array_filter([
                'country'      => $validated['country'] ?? null,
                'country_code' => $validated['country_code'] ?? null,
                'phone'        => $validated['phone'] ?? null,
            ]));

            /** -----------------------
             *  Profile Image Upload
             * ---------------------- */
            $profile = UserProfile::where('user_id', $userId)->first();
            $profileImagePath = $profile->profile_image ?? null;

            if ($request->hasFile('profile_image')) {
                // delete old image
                if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
                    Storage::disk('public')->delete($profileImagePath);
                }

                $profileImagePath = $request
                    ->file('profile_image')
                    ->store('profile-images', 'public');
            }

            /** -----------------------
             *  Update / Create Profile
             * ---------------------- */
            $profile = UserProfile::updateOrCreate(
                ['user_id' => $userId],
                array_filter([
                    'age_band'      => $validated['age_band'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'dining_budget' => $validated['dining_budget'] ?? null,
                    'has_dogs'      => $validated['has_dogs'] ?? null,
                    'profile_image' => $profileImagePath,
                ])
            );

            /** -----------------------
             *  Sync Interests
             * ---------------------- */
            if (isset($validated['interests'])) {
                $user->interests()->sync($validated['interests']);
            }

            /** -----------------------
             *  Response
             * ---------------------- */
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user->only([
                        'id',
                        'full_name',
                        'email',
                        'country',
                        'country_code',
                        'phone'
                    ]),
                    'profile' => [
                        'age_band'      => $profile->age_band,
                        'city'          => $profile->city,
                        'dining_budget' => $profile->dining_budget,
                        'has_dogs'      => $profile->has_dogs,
                        'profile_image' => $profile->profile_image
                            ? asset('storage/' . $profile->profile_image)
                            : null,
                    ],
                    'interests' => $user->interests()->get([
                        'interests.id',
                        'interests.name',
                        'interests.icon'
                    ])
                ]
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}