<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * List sub-categories
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.sub_categories.index', compact('subCategories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = CatalogCategory::where('status', 1)->get();

        return view('admin.sub_categories.create', compact('categories'));
    }

    /**
     * Store new sub-category
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:catalog_categories,id',
            'name'        => 'required|string|max:255',
            // 'icon'        => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            // 'icon'        => $request->icon,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub-category created successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories  = CatalogCategory::where('status', 1)->get();

        return view('admin.sub_categories.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update sub-category
     */
    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:catalog_categories,id',
            'name'        => 'required|string|max:255',
            // 'icon'        => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        $subCategory->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            // 'icon'        => $request->icon,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.sub-categories.index')
            ->with('success', 'Sub-category updated successfully');
    }

    public function show($id)
    {
        $subCategory = SubCategory::with('category')->findOrFail($id);

        return view('admin.sub_categories.show', compact('subCategory'));
    }

    /**
     * Delete sub-category
     */
    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();

        return redirect()
            ->route('sub-categories.index')
            ->with('success', 'Sub-category deleted successfully');
    }

    public function toggleStatus($id)
    {
        $item = SubCategory::findOrFail($id);
        // dd($item);
        $item->update([
            'status' => !$item->status
        ]);

        return back()->with('success', 'Status updated.');
    }
}
