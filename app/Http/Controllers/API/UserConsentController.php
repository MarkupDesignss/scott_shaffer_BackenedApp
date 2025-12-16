<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Policy;
use App\Models\UserConsent;
use Illuminate\Support\Facades\Auth;

class UserConsentController extends Controller
{
    // 1. Get Consent Details
    public function index(Request $request)
    {
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
        ]);
    }

    /**
     * Get single policy by slug
     * API: GET /api/policies/{slug}
     */
    public function show($slug)
    {
        $policy = Policy::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'name'        => $policy->name,
                'description' => $policy->description,
                'version'     => $policy->version
            ]
        ]);
    }


    // 2. Update/Store Consent
    public function update(Request $request)
    {
        $request->validate([
            'accepted_terms_privacy' => 'boolean',
            'campaign_marketing' => 'boolean',
        ]);

        $user_id = $request->user_id;

        $consent = UserConsent::updateOrCreate(
            ['user_id' => $user_id],
            [
                'accepted_terms_privacy' => $request->accepted_terms_privacy ?? false,
                'campaign_marketing' => $request->campaign_marketing ?? false,
                'accepted_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Consent updated successfully.',
            'data' => $consent
        ]);
    }


    // 3. Export User Data (GDPR-like)
    public function exportUserData(Request $request)
    {
        $user = $request->user();

        $data = [
            'user' => $user,
            'consent' => $user->consent ?? null,
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
