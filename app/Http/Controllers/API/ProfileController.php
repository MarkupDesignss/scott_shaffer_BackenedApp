<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $user = User::with(['profile', 'interests:id,name'])
                ->select('id', 'full_name', 'email', 'phone', 'country', 'country_code')
                ->find($userId);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            /** -----------------------
             *  Interest → Category → Items
             * ---------------------- */
            $interestsData = [];

            foreach ($user->interests as $interest) {

                $categories = DB::table('catalog_categories')
                    ->where('interest_id', $interest->id)
                    ->where('status', 1)
                    ->select('id', 'name', 'slug', 'icon', 'color')
                    ->get()
                    ->map(function ($category) {

                        $items = DB::table('catalog_items')
                            ->where('category_id', $category->id)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->select('id', 'name', 'description', 'image_url')
                            ->get();

                        $category->items = $items;
                        return $category;
                    });

                $interestsData[] = [
                    'id'         => $interest->id,
                    'name'       => $interest->name,
                    'categories' => $categories
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => [
                    'user'      => $user,
                    'interests' => $interestsData
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
                'age_band'       => 'required|in:18-24,25-34,35-44,45+',
                'city'           => 'required|string|max:100',
                'dining_budget'  => 'required|in:under_100,100_300,300_500,500_plus',
                'has_dogs'       => 'required|boolean',
            ]);

            $profile = UserProfile::updateOrCreate(
                ['user_id' => $validated['user_id']],
                $validated
            );

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
                'full_name'     => 'nullable|string|max:255',
                'email'         => 'nullable|email|unique:users,email,' . $userId,
                'country'       => 'nullable|string|max:100',
                'country_code'  => 'nullable|string|max:5',
                'phone'         => 'nullable|string|unique:users,phone,' . $userId,

                'age_band'      => 'nullable|in:18-24,25-34,35-44,45+',
                'city'          => 'nullable|string|max:100',
                'dining_budget' => 'nullable|in:under_100,100_300,300_500,500_plus',
                'has_dogs'      => 'nullable|boolean',
            ]);

            $user = User::findOrFail($userId);

            $user->update(array_filter([
                'full_name'    => $validated['full_name'] ?? null,
                'email'        => $validated['email'] ?? null,
                'country'      => $validated['country'] ?? null,
                'country_code' => $validated['country_code'] ?? null,
                'phone'        => $validated['phone'] ?? null,
            ]));

            $profile = UserProfile::updateOrCreate(
                ['user_id' => $userId],
                array_filter([
                    'age_band'      => $validated['age_band'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'dining_budget' => $validated['dining_budget'] ?? null,
                    'has_dogs'      => $validated['has_dogs'] ?? null,
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
                'message' => 'Profile update failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
