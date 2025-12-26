<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view('admin.segments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'filters' => 'required|array',
        ]);

        Segment::create([
            'name'    => $validated['name'],
            'filters' => $validated['filters'],
        ]);

        return redirect()->route('admin.segments.index')
            ->with('success', 'Segment created successfully');
    }

    public function edit($id)
    {
        $segment = Segment::findOrFail($id);
        return view('admin.segments.edit', compact('segment'));
    }

    public function update(Request $request, $id)
    {
        $segment = Segment::findOrFail($id);
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'filters' => 'required|array',
        ]);

        $segment->update($validated);

        return redirect()->route('admin.segments.index')
            ->with('success', 'Segment updated successfully');
    }

    public function destroy($id)
    {
        $segment = Segment::findOrFail($id);
        $segment->delete();

        return back()->with('success', 'Segment deleted');
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
