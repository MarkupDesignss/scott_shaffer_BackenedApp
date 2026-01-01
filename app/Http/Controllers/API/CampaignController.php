<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Segment;
use Carbon\Carbon;


class CampaignController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $now  = Carbon::now();

            // 1️⃣ User ke interest IDs
            $userIntrestIds = $user->interests()->pluck('interests.id')->toArray();

            // 1a️⃣ Optional: specific interest filter from query parameter
            $specificInterest = $request->query('interest_id');
            if ($specificInterest) {
                $userIntrestIds[] = (int) $specificInterest;
            }

            if (empty($userIntrestIds)) {
                return response()->json([
                    'success'   => true,
                    'campaigns' => []
                ]);
            }

            // 2️⃣ Matching Segments (filters->intrest_ids overlap) and status = active
            $segmentIds = Segment::where('status', 'active')
                ->where(function ($q) use ($userIntrestIds) {
                    foreach ($userIntrestIds as $intrestId) {
                        $q->orWhereJsonContains(
                            'filters->intrest_ids',
                            (string) $intrestId
                        );
                    }
                })
                ->pluck('id');

            if ($segmentIds->isEmpty()) {
                return response()->json([
                    'success'   => true,
                    'campaigns' => []
                ]);
            }

            // 3️⃣ Campaigns mapped with those segments
            $campaigns = Campaign::with('segments')
                ->where('status', 'live')

                // ✅ Campaign must be started
                ->where(function ($q) use ($now) {
                    $q->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', $now);
                })

                // ✅ Campaign must not be ended
                ->where(function ($q) use ($now) {
                    $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', $now);
                })

                ->whereHas('segments', function ($q) use ($segmentIds) {
                    $q->whereIn('segments.id', $segmentIds)
                        ->where('status', 'active');
                })
                ->latest()
                ->get();

            return response()->json([
                'success'   => true,
                'campaigns' => $campaigns,
                'message'   => 'campaigns fetched'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data'    => $th->getMessage(),
                'message' => 'campaigns cannot be fetched'
            ]);
        }
    }
}
