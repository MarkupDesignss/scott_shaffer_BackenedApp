<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Segment;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            // 1️⃣ User ke interest IDs
            $userIntrestIds = $user->interests()->pluck('interests.id')->toArray();
            if (empty($userIntrestIds)) {
                return response()->json([
                    'success' => true,
                    'campaigns' => []
                ]);
            }

            // 2️⃣ Matching Segments (filters->intrest_ids overlap)
            $segmentIds = Segment::where(function ($q) use ($userIntrestIds) {
                foreach ($userIntrestIds as $intrestId) {
                    $q->orWhereJsonContains(
                        'filters->intrest_ids',
                        (string) $intrestId
                    );
                }
            })->pluck('id');

            if ($segmentIds->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'campaigns' => []
                ]);
            }

            // 3️⃣ Campaigns mapped with those segments
            $campaigns = Campaign::with('segments')
                ->where('status', 'live')
                ->whereHas('segments', function ($q) use ($segmentIds) {
                    $q->whereIn('segments.id', $segmentIds);
                })
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'campaigns' => $campaigns,
                "message"   => "campsigns fetched"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => $th->getMessage(),
                "message"   => "campsigns cant be fetched"
            ]);
        }
    }
}