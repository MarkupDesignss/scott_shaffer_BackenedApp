<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\Interest;
use App\Models\Intrest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogCategoryController extends Controller
{
    /**
     * List categories
     */
    public function index()
    {
        $categories = CatalogCategory::with('interest')
            ->latest()
            ->paginate(10);

        return view('admin.catalog.categories.index', compact('categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $interests = Intrest::where('is_active', '1')->get();
        return view('admin.catalog.categories.create', compact('interests'));
    }

    /**
     * Show single category
     */
    public function show($id)
    {
        $category = CatalogCategory::with('interest', 'items')->findOrFail($id);

        return view('admin.catalog.categories.show', compact('category'));
    }

    /**
     * Store category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            //'icon'        => 'nullable|string',
            'interest_id' => 'required|exists:interests,id',
            'status'      => 'required|in:0,1',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        CatalogCategory::create($validated);

        return redirect()
            ->route('admin.catalog-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $category  = CatalogCategory::findOrFail($id);
        $interests = Intrest::where('is_active', '1')->get();

        return view('admin.catalog.categories.edit', compact('category', 'interests'));
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        $category = CatalogCategory::findOrFail($id);
        // dd($request->all());
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            //'icon'        => 'nullable|string',
            'interest_id' => 'required|exists:interests,id',
            'status'      => 'required|in:0,1',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()
            ->route('admin.catalog-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        CatalogCategory::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'Category deleted successfully.');
    }
    public function toggleStatus($id)
    {
        $catagory = CatalogCategory::findOrFail($id);
        $catagory->update([
            'status' => !$catagory->status
        ]);

        return back()->with('success', 'Status updated.');
    }
}