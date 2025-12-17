<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CatalogItem;
use App\Models\CatalogCategory;

class CatalogItemController extends Controller
{
    /**
     * List catalog items
     */
    public function index(Request $request)
    {
        $items = CatalogItem::with('category')
            ->when($request->category_id, function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(10);

        $categories = CatalogCategory::where('status', 'active')->get();

        return view('admin.catalog.items.index', compact('items', 'categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = CatalogCategory::where('status', '1')->get();

        return view('admin.catalog.items.create', compact('categories'));
    }

    /**
     * Store new catalog item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'category_id' => 'required|exists:catalog_categories,id',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string',
            'status'      => 'required|in:1,0',
        ]);
        // dd($request->all());

        CatalogItem::create($validated);

        return redirect()
            ->route('admin.catalog-items.index')
            ->with('success', 'Catalog item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = CatalogItem::with('category')->findOrFail($id);
        return view('admin.catalog.items.show', compact('item'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $item = CatalogItem::findOrFail($id);
        $categories = CatalogCategory::where('status', '1')->get();

        return view('admin.catalog.items.edit', compact('item', 'categories'));
    }

    /**
     * Update catalog item
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $item = CatalogItem::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'category_id' => 'required|exists:catalog_categories,id',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|string',
            'status'      => 'required|in:1,0',
        ]);

        $item->update($validated);

        return redirect()
            ->route('admin.catalog-items.index')
            ->with('success', 'Catalog item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        CatalogItem::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'Catalog item deleted successfully.');
    }
    public function toggleStatus($id)
    {
        $catagory = CatalogItem::findOrFail($id);
        // dd($catagory);
        $catagory->update([
            'status' => !$catagory->status
        ]);

        return back()->with('success', 'Status updated.');
    }
}
