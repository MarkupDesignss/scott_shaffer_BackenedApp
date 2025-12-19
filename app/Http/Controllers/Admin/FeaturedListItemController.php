<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedList;
use App\Models\FeaturedListItem;
use App\Models\CatalogItem;
use Illuminate\Http\Request;

class FeaturedListItemController extends Controller
{
    public function index()
    {
        return view('admin.featured_lists.items.index', [
            'items' => FeaturedListItem::with([
                'catalogItem',
                'featuredList'
            ])->latest()->get()
        ]);
    }


    public function create()
    {
        return view('admin.featured_lists.items.create', [
            'featuredLists' => FeaturedList::orderBy('title')->get(),
            'catalogItems'  => CatalogItem::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'featured_list_id' => 'required|exists:featured_lists,id',
            'catalog_item_id'  => 'required|exists:catalog_items,id',
            'position'         => 'required|integer|min:1'
        ]);
        $list = FeaturedList::findOrFail($validated['featured_list_id']);

        // 1️⃣ Check list size limit
        if ($list->items()->count() >= $list->list_size) {
            return back()
                ->withErrors('List size limit reached')
                ->withInput();
        }

        // 2️⃣ Prevent duplicate item in same list
        $alreadyExists = $list->items()
            ->where('catalog_item_id', $validated['catalog_item_id'])
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors('This item is already added to the selected featured list')
                ->withInput();
        }

        FeaturedListItem::create($validated);

        return redirect()
            ->route('admin.featured-list-items.index')
            ->with('success', 'Item added successfully');
    }


    public function edit($id)
    {
        $item = FeaturedListItem::findOrFail($id);
        // dd($item);
        return view('admin.featured_lists.items.edit', [
            'item'          => $item,
            'featuredLists' => FeaturedList::orderBy('title')->get(),
            'catalogItems'  => CatalogItem::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = FeaturedListItem::findOrFail($id);
        // dd($request->all());
        $validated = $request->validate([
            'featured_list_id' => 'required|exists:featured_lists,id',
            'catalog_item_id'  => 'required|exists:catalog_items,id',
            'position'         => 'required|integer|min:1'
        ]);

        if (
            $item->featured_list_id !== (int) $validated['featured_list_id']
        ) {
            $list = FeaturedList::findOrFail($validated['featured_list_id']);

            if ($list->items()->count() >= $list->list_size) {
                return back()->withErrors('List size limit reached')->withInput();
            }
        }

        $item->update($validated);

        return redirect()
            ->route('admin.featured-list-items.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy($id)
    {
        $item = FeaturedListItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Item deleted successfully');
    }
}
