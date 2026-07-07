<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use App\Models\FeaturedList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeaturedListController extends Controller
{
    public function index()
    {
        $lists = FeaturedList::with('category')
            ->orderBy('display_order')
            ->get();

        return view('admin.featured_lists.index', compact('lists'));
    }

    public function create()
    {
        $categories = CatalogCategory::where('status', '1')->get();
        return view('admin.featured_lists.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:150',
            'category_id'   => 'required|exists:catalog_categories,id',
            'list_size'     => 'required|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'status'        => 'required|in:draft,live',
            'image'         => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('featured_lists', 'public');
        }

        FeaturedList::create($validated + [
            'created_by' => auth('admin')->id(),
        ]);

        return redirect()
            ->route('admin.featured-lists.index')
            ->with('success', 'Featured list created successfully');
    }

    public function edit(FeaturedList $featuredList)
    {
        $featuredList->load('items.catalogItem');
        $categories = CatalogCategory::where('status', '1')->get();
        $catalogItems = CatalogItem::where('status', '1')->get();

        return view('admin.featured_lists.edit', compact(
            'featuredList',
            'categories',
            'catalogItems'
        ));
    }
    
        public function show($id)
    {
        $featuredList = FeaturedList::find($id);
        return response()->view(
            'admin.featured_lists.show',
            compact('featuredList'),
            200
        );
    }

    public function update(Request $request, FeaturedList $featuredList)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:150',
            'category_id'   => 'required|exists:catalog_categories,id',
            'list_size'     => 'required|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'status'        => 'required|in:draft,live',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {

            // delete old image
            if ($featuredList->image && Storage::disk('public')->exists($featuredList->image)) {
                Storage::disk('public')->delete($featuredList->image);
            }

            $validated['image'] = $request->file('image')
                ->store('featured_lists', 'public');
        }

        $featuredList->update($validated);

        return redirect()
            ->route('admin.featured-lists.index')
            ->with('success', 'Featured list updated successfully');
    }

    public function toggleStatus(FeaturedList $featuredList)
    {
        $featuredList->update([
            'status' => $featuredList->status === 'live' ? 'draft' : 'live'
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(FeaturedList $featuredList)
    {
        if ($featuredList->image && Storage::disk('public')->exists($featuredList->image)) {
            Storage::disk('public')->delete($featuredList->image);
        }

        $featuredList->delete();

        return back()->with('success', 'Featured list deleted');
    }
}
