<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intrest;
use App\Models\Segment;
use App\Models\SegmentExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SegmentController extends Controller
{
    public function index()
    {
        $segments = Segment::latest()->paginate(20);
        return view('admin.segments.index', compact('segments'));
    }

    public function create()
    {
        $intrest = Intrest::get();
        return view('admin.segments.create', compact('intrest'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'status'  => 'required|in:active,inactive',
            'filters' => 'required|array',
        ]);
        Segment::create([
            'name'    => $validated['name'],
            'status'    => $validated['status'],
            'filters' => $validated['filters'],
        ]);

        return redirect()->route('admin.segments.index')
            ->with('success', 'Segment created successfully');
    }

    public function edit($id)
    {
        $segment = Segment::findOrFail($id);
        $intrest = Intrest::get();

        return view('admin.segments.edit', compact('segment', 'intrest'));
    }

    public function update(Request $request, $id)
    {
        $segment = Segment::findOrFail($id);
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'status'  => 'required|in:active,inactive',
            'filters' => 'required|array',
        ]);

        // dd($validated);
        $segment->update($validated);

        return redirect()->route('admin.segments.index')
            ->with('success', 'Segment updated successfully');
    }
    public function destroy($id)
    {
        $segment = Segment::with('campaigns')->findOrFail($id);

        foreach ($segment->campaigns as $campaign) {
            $campaign->delete();
        }

        $segment->delete();

        return back()->with('success', 'Segment and related campaigns deleted');
    }

    /**
     * Preview estimated users
     */
    public function estimate($id)
    {
        $segment = Segment::findOrFail($id);
        // Example logic – replace with real query
        $count = rand(50, 5000);

        $segment->update([
            'estimated_users' => $count
        ]);

        return response()->json([
            'success' => true,
            'estimated_users' => $count
        ]);
    }

    /**
     * Export CSV with hashed identifiers
     */
    public function export($id)
    {
        $segment = Segment::findOrFail($id);
        $fileName = 'segments/segment_' . $segment->id . '_' . time() . '.csv';

        $rows = [
            ['hashed_user_id'],
            [hash('sha256', 'user1')],
            [hash('sha256', 'user2')],
        ];

        $handle = fopen(storage_path('app/' . $fileName), 'w');
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        SegmentExport::create([
            'segment_id'       => $segment->id,
            'admin_id'         => Auth::guard('admin')->id(),
            'file_path'        => $fileName,
            'filters_snapshot' => $segment->filters,
        ]);

        return back()->with('success', 'Segment exported successfully');
    }

    /**
     * Export logs
     */
    public function exports($id)
    {
        $segment = Segment::findOrFail($id);
        $exports = $segment->exports()->latest()->get();
        return view('admin.segments.exports', compact('segment', 'exports'));
    }
}