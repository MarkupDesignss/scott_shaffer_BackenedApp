<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use App\Models\FeaturedList;
use Illuminate\Http\Request;

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
        return view('admin.featured_lists.create', [
            'categories' => $categories
        ]);
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
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('featured_lists'), $imageName); // Move to public/featured_lists
            $validated['image'] = 'featured_lists/' . $imageName;
        }

        FeaturedList::create($validated + [
            'created_by' => auth('admin')->id(),
        ]);

        return redirect()
            ->route('admin.featured-lists.index')
            ->with('success', 'Featured list created successfully');
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
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('featured_lists'), $imageName);
            $validated['image'] = 'featured_lists/' . $imageName;
        } else {
            unset($validated['image']); // Don't update if no image uploaded
        }

        $featuredList->update($validated);

        return redirect()
            ->route('admin.featured-lists.index')
            ->with('success', 'Featured list updated successfully');
    }


    public function edit(FeaturedList $featuredList)
    {
        $featuredList->load('items.catalogItem');
        $categories = CatalogCategory::where('status', '1')->get();
        $categoriesItems = CatalogItem::where('status', '1')->get();
        return view('admin.featured_lists.edit', [
            'featuredList' => $featuredList,
            'categories'   => $categories,
            'catalogItems' => $categoriesItems
        ]);
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
        $featuredList->delete();

        return back()->with('success', 'Featured list deleted');
    }
}
