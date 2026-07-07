<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Segment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CampaignController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $now  = Carbon::now();
    
            //  User interest IDs
            $userIntrestIds = $user->interests()->pluck('interests.id')->toArray();
    
            // Optional specific interest filter
            $specificInterest = $request->query('interest_id');
            if ($specificInterest) {
                $userIntrestIds[] = (int) $specificInterest;
            }
    
            if (empty($userIntrestIds)) {
                return response()->json([
                    'success'   => true,
                    'campaigns' => [],
                    'message'   => 'No interests found'
                ]);
            }
    
            //  Active segments matching interests
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
                    'campaigns' => [],
                    'message'   => 'No matching segments'
                ]);
            }
    
            //  Live campaigns mapped with segments
            $campaigns = Campaign::with('segments')
                ->where('status', 'live')
                ->where(function ($q) use ($now) {
                    $q->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', $now);
                })
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
    
            // Convert image_url to full public URL
            $campaigns->transform(function ($campaign) {
                $campaign->image_url = $campaign->image_url
                    ? asset('storage/' . $campaign->image_url)
                    : null;
                return $campaign;
            });
    
            return response()->json([
                'success'   => true,
                'campaigns' => $campaigns,
                'message'   => 'Campaigns fetched successfully'
            ]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Campaigns cannot be fetched',
                'error'   => $th->getMessage()
            ], 500);
        }
    }


}
