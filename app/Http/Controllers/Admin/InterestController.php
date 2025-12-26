<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Intrest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'name'       => 'required|string|max:255',
            'icon_image' => 'required|image|mimes:png,svg,webp|max:1024',
            'is_active'  => 'nullable|boolean',
        ]);

        // Upload icon image
        $iconPath = null;
        if ($request->hasFile('icon_image')) {
            $iconPath = $request->file('icon_image')
                                ->store('interest-icons', 'public');
        }

        Intrest::create([
            'name'       => $request->name,
            'icon' => $iconPath,
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.interest.index')
            ->with('success', 'Interest created successfully.');
    }


    public function edit(Intrest $interest)
    {
        return view('admin.interest.edit', compact('interest'));
    }



    public function update(Request $request, Intrest $interest)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'icon_image' => 'nullable|image|mimes:png,svg,webp|max:1024',
            'is_active'  => 'nullable|boolean',
        ]);

        // ✅ Only delete if old image path exists AND is not null
        if ($request->hasFile('icon_image')) {

            if (!empty($interest->icon)
                && Storage::disk('public')->exists($interest->icon)) {

                Storage::disk('public')->delete($interest->icon);
            }

            $interest->icon = $request->file('icon_image')
                                            ->store('interest-icons', 'public');
        }

        $interest->name = $request->name;
        $interest->is_active = $request->has('is_active');
        $interest->save();

        return redirect()
            ->route('admin.interest.index')
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