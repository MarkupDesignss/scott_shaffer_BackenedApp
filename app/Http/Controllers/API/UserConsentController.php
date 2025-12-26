<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Policy;
use App\Models\UserConsent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserConsentController extends Controller
{
    /**
     * 1. Get all active policies
     * GET: /api/policies
     */
    public function index()
    {
        try {
            $policies = Policy::where('is_active', true)
                ->orderBy('order')
                ->get([
                    'id',
                    'name',
                    'description'
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Policies fetched successfully',
                'data'    => $policies
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch policies',
            ], 500);
        }
    }

    /**
     * 2. Get single policy by slug
     * GET: /api/policies/{slug}
     */
    public function show(string $slug)
    {
        try {
            $policy = Policy::where('slug', $slug)
                ->where('is_active', true)
                ->first();

            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Policy not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'name'        => $policy->name,
                    'description' => $policy->description,
                    'version'     => $policy->version,
                ],
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch policy',
                'reason'  => $th->getMessage()
            ], 500);
        }
    }

    /**
     * 3. Update / Store User Consent
     * POST/PUT: /api/user-consent
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user_id;
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $validated = $request->validate([
                'accepted_terms_privacy' => 'nullable|boolean',
                'campaign_marketing'     => 'nullable|boolean',
            ]);

            $consent = UserConsent::updateOrCreate(
                ['user_id' => $user],
                [
                    'accepted_terms_privacy' => $validated['accepted_terms_privacy'] ?? false,
                    'campaign_marketing'     => $validated['campaign_marketing'] ?? false,
                    'accepted_at'            => now(),
                ]
            );
            User::where('id', $user)->update([
                'is_consent_completed' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consent updated successfully',
                'data'    => $consent,
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
                'message' => 'Failed to update consent',
                'reason'  => $th->getMessage()
            ], 500);
        }
    }

    /**
     * 4. Export User Data (GDPR-like)
     * GET: /api/user-data/export
     */
    public function exportUserData(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $data = [
                'user'    => $user,
                'consent' => $user->consent ?? null,
            ];

            return response()->json([
                'success' => true,
                'data'    => $data,
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to export user data',
                'reason'  => $th->getMessage()
            ], 500);
        }
    }
}
