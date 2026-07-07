<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatalogItem;
use App\Models\CatalogCategory;
use App\Models\SubCategory;

class CatalogItemController extends Controller
{
    /**
     * List items
     */
    public function index(Request $request)
    {
        $items = CatalogItem::with(['subCategory.category'])
            ->when($request->sub_category_id, function ($q) use ($request) {
                $q->where('sub_category_id', $request->sub_category_id);
            })
            ->latest()
            ->paginate(10);

        $categories = CatalogCategory::where('status', 1)->get();
        $subCategories = SubCategory::where('status', 1)->get();

        return view('admin.catalog.items.index', compact(
            'items',
            'categories',
            'subCategories'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = CatalogCategory::where('status', 1)->get();

        return view('admin.catalog.items.create', compact('categories'));
    }

    /**
     * Store item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'category_id' => 'required',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',

            'image_url'       => 'nullable|url|required_without:image_upload',
            'image_upload'    => 'nullable|image|mimes:jpg,jpeg,png,webp|required_without:image_url',

            'status'          => 'required|boolean',
        ]);

        // Image handling (URL or Upload)
        if ($request->hasFile('image_upload')) {
            $validated['image_url'] =
                $request->file('image_upload')->store('category-items', 'public');
        }

        CatalogItem::create($validated);

        return redirect()
            ->route('admin.catalog-items.index')
            ->with('success', 'Item created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $item = CatalogItem::with('subCategory.category')->findOrFail($id);
        $categories = CatalogCategory::where('status', 1)->get();

        return view('admin.catalog.items.edit', compact(
            'item',
            'categories'
        ));
    }

    /**
     * Update item
     */
    public function update(Request $request, $id)
    {
        $item = CatalogItem::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',

            'image_url'       => 'nullable|string',
            'image_upload'    => 'nullable|image|mimes:jpg,jpeg,png,webp',

            'status'          => 'required|boolean',
        ]);

        if ($request->hasFile('image_upload')) {
            $validated['image_url'] =
                $request->file('image_upload')->store('category-items', 'public');
        }

        $item->update($validated);

        return redirect()
            ->route('admin.catalog-items.index')
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Delete item
     */
    public function destroy($id)
    {
        CatalogItem::findOrFail($id)->delete();

        return back()->with('success', 'Item deleted successfully.');
    }

    /**
     * Toggle status
     */
    public function toggleStatus($id)
    {
        $item = CatalogItem::findOrFail($id);

        $item->update([
            'status' => !$item->status
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function show($id)
    {
        $item = CatalogItem::with([
            'subCategory',
            'subCategory.category'
        ])->findOrFail($id);

        return view('admin.catalog.items.show', compact('item'));
    }

    /**
     * AJAX: Get sub-categories by category
     */
    public function getSubCategories($categoryId)
    {
        return SubCategory::where('category_id', $categoryId)
            ->where('status', 1)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
