<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Segment;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('segments')
            ->latest()
            ->paginate(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    // public function create()
    // {
    //     return view('admin.campaigns.create');
    // }
    public function create()
    {
        $segments = Segment::get();
        return view('admin.campaigns.create', compact('segments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:60',
            'subtitle' => 'nullable|string|max:120',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cta_text' => 'nullable|string|max:30',
            'cta_url' => 'nullable|url',
            'status' => 'required|in:draft,live,paused',
            'requires_consent' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'segments' => 'required|array',
            'segments.*' => 'exists:segments,id',
        ]);

        // IMAGE UPLOAD (move)
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/campaigns'), $fileName);

            $data['image_url'] = 'uploads/campaigns/' . $fileName;
        }
        $campaign = Campaign::create($data);
        $campaign->segments()->sync($data['segments']);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign created successfully');
    }




    public function edit(Campaign $campaign)
    {
        $segments = Segment::get();
        $selectedSegments = $campaign->segments()->pluck('segments.id')->toArray();

        return view('admin.campaigns.edit', compact(
            'campaign',
            'segments',
            'selectedSegments'
        ));
    }


    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:60',
            'subtitle' => 'nullable|string|max:120',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cta_text' => 'nullable|string|max:30',
            'cta_url' => 'nullable|url',
            'status' => 'required|in:draft,live,paused',
            'requires_consent' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'segments' => 'required|array',
            'segments.*' => 'exists:segments,id',
        ]);

        // IMAGE REPLACE (move)
        if ($request->hasFile('image_url')) {

            if ($campaign->image_url && file_exists(public_path($campaign->image_url))) {
                unlink(public_path($campaign->image_url));
            }

            $file = $request->file('image_url');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/campaigns'), $fileName);

            $data['image_url'] = 'uploads/campaigns/' . $fileName;
        }

        $campaign->update($data);
        $campaign->segments()->sync($data['segments']);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign updated successfully');
    }


    public function toggleStatus($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update([
            'status' => $campaign->status === 'live' ? 'draft' : 'live'
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();


        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully');
    }
}
