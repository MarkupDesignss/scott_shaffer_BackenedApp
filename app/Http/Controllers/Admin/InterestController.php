<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Intrest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function index()
    {
        $interests = Intrest::latest()->paginate(10);
        return view('admin.interest.index', compact('interests'));
    }

    public function create()
    {
        return view('admin.interest.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        Intrest::create([
            'name'      => $request->name,
            'icon'      => $request->icon,
            'is_active' => true,
        ]);

        return redirect()->route('admin.interest.index')
            ->with('success', 'Interest created successfully.');
    }

    public function edit(Intrest $interest)
    {
        return view('admin.interest.edit', compact('interest'));
    }

    public function update(Request $request, Intrest $interest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $interest->update([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.interest.index')
            ->with('success', 'Interest updated successfully.');
    }

    public function destroy(Intrest $interest)
    {
        $interest->delete();

        return redirect()->route('admin.interest.index')
            ->with('success', 'Interest deleted successfully.');
    }

    public function toggleStatus(Intrest $interest)
    {
        $interest->update([
            'is_active' => !$interest->is_active
        ]);

        return back()->with('success', 'Status updated.');
    }
}