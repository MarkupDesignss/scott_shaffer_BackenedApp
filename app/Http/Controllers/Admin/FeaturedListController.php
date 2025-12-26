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
            'list_size'     => 'required',
            'display_order' => 'required|integer|min:0',
            'image_url'     => 'nullable|url|required_without:image_upload',
            'image_upload'  => 'nullable|image|mimes:jpg,jpeg,png,webp|required_without:image_url',
            'status'        => 'required|in:draft,live'
        ]);

        if ($request->hasFile('image_upload')) {
            $path = $request->file('image_upload')->store('featured_lists', 'public');
            $validated['image'] = $path;
            unset($validated['image_upload']);
        }

        FeaturedList::create(
            $validated + ['created_by' => auth('admin')->id()]
        );

        return redirect()
            ->route('admin.featured-lists.index')
            ->with('success', 'Featured list created');
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

    public function update(Request $request, FeaturedList $featuredList)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title'         => 'required|string|max:150',
            'category_id'   => 'required|exists:catalog_categories,id',
            'list_size'     => 'required',
            'display_order' => 'required|integer|min:0',
            'status'        => 'required|in:draft,live'
        ]);

        $featuredList->update($validated);

        return redirect()
            ->route('admin.featured-lists.index')
            ->with('success', 'Featured list updated');
    }

    public function destroy(FeaturedList $featuredList)
    {
        $featuredList->delete();

        return back()->with('success', 'Featured list deleted');
    }
}