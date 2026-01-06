<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intrest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
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

    $iconPath = null;

    if ($request->hasFile('icon_image')) {

        $uploadPath = public_path('interest-icons');

        // create folder if not exists
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = $request->file('icon_image');
        $fileName = uniqid().'_'.$file->getClientOriginalName();

        $file->move($uploadPath, $fileName);

        // path saved in DB
        $iconPath = 'interest-icons/'.$fileName;
    }

    Intrest::create([
        'name'      => $request->name,
        'icon'      => $iconPath,
        'is_active' => $request->has('is_active'),
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

    if ($request->hasFile('icon_image')) {

        // delete old file
        if (!empty($interest->icon) && file_exists(public_path($interest->icon))) {
            unlink(public_path($interest->icon));
        }

        $uploadPath = public_path('interest-icons');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = $request->file('icon_image');
        $fileName = uniqid().'_'.$file->getClientOriginalName();

        $file->move($uploadPath, $fileName);

        $interest->icon = 'interest-icons/'.$fileName;
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
        if (!empty($interest->icon) &&
            Storage::disk('public')->exists($interest->icon)) {
            Storage::disk('public')->delete($interest->icon);
        }

        $interest->delete();

        return redirect()
            ->route('admin.interest.index')
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
