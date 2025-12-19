<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }


    public function create()
    {
        return view('admin.campaigns.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:60',
            'subtitle' => 'nullable|string|max:120',
            'image_url' => 'nullable|url',
            'cta_text' => 'nullable|string|max:30',
            'cta_url' => 'nullable|url',
            'status' => 'required|in:draft,live,paused',
            'requires_consent' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);


        Campaign::create($data);


        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign created successfully');
    }


    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }


    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:60',
            'subtitle' => 'nullable|string|max:120',
            'image_url' => 'nullable|url',
            'cta_text' => 'nullable|string|max:30',
            'cta_url' => 'nullable|url',
            'status' => 'required|in:draft,live,paused',
            'requires_consent' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);


        $campaign->update($data);


        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign updated successfully');
    }


    public function destroy(Campaign $campaign)
    {
        $campaign->delete();


        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully');
    }
}
