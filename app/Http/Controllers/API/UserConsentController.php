<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserConsent;
use Illuminate\Support\Facades\Auth;

class UserConsentController extends Controller
{
    // 1. Get Consent Details
    public function show(Request $request)
    {
        $user = $request->user();

        $consent = UserConsent::where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'data' => $consent
        ]);
    }


    // 2. Update/Store Consent
    public function update(Request $request)
    {
        $request->validate([
            'accepted_terms_privacy' => 'boolean',
            'campaign_marketing' => 'boolean',
        ]);

        $user = Auth::user();

        $consent = UserConsent::updateOrCreate(
            ['user_id' => $user->id],
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
        $user = Auth::user();

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