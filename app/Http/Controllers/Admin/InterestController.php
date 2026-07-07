<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intrest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\CatalogCategory;
use App\Models\ListModel;
use App\Models\ListItem;
use App\Models\User;


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

    //   public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'       => 'required|string|max:255',
    //         'icon_image' => 'required|image|mimes:png,svg,webp|max:1024',
    //         'is_active'  => 'nullable|boolean',
    //     ]);

    //     $iconPath = null;

    //     if ($request->hasFile('icon_image')) {

    //         $uploadPath = public_path('interest-icons');

    //         // create folder if not exists
    //         if (!file_exists($uploadPath)) {
    //             mkdir($uploadPath, 0755, true);
    //         }

    //         $file = $request->file('icon_image');
    //         $fileName = uniqid().'_'.$file->getClientOriginalName();

    //         $file->move($uploadPath, $fileName);

    //         // path saved in DB
    //         $iconPath = 'interest-icons/'.$fileName;
    //     }

    //     Intrest::create([
    //         'name'      => $request->name,
    //         'icon'      => $iconPath,
    //         'is_active' => $request->has('is_active'),
    //     ]);

    //     return redirect()
    //         ->route('admin.interest.index')
    //         ->with('success', 'Interest created successfully.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            // 'name'       => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:interests,name',
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

        // Only delete if old image path exists AND is not null
        if ($request->hasFile('icon_image')) {

            if (
                !empty($interest->icon)
                && Storage::disk('public')->exists($interest->icon)
            ) {

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

    //     public function update(Request $request, Intrest $interest)
    // {
    //     $request->validate([
    //         'name'       => 'required|string|max:255',
    //         'icon_image' => 'nullable|image|mimes:png,svg,webp|max:1024',
    //         'is_active'  => 'nullable|boolean',
    //     ]);

    //     if ($request->hasFile('icon_image')) {

    //         // delete old file
    //         if (!empty($interest->icon) && file_exists(public_path($interest->icon))) {
    //             unlink(public_path($interest->icon));
    //         }

    //         $uploadPath = public_path('interest-icons');

    //         if (!file_exists($uploadPath)) {
    //             mkdir($uploadPath, 0755, true);
    //         }

    //         $file = $request->file('icon_image');
    //         $fileName = uniqid().'_'.$file->getClientOriginalName();

    //         $file->move($uploadPath, $fileName);

    //         $interest->icon = 'interest-icons/'.$fileName;
    //     }

    //     $interest->name = $request->name;
    //     $interest->is_active = $request->has('is_active');
    //     $interest->save();

    //     return redirect()
    //         ->route('admin.interest.index')
    //         ->with('success', 'Interest updated successfully.');
    // }

    public function destroy(Intrest $interest)
    {
        if (
            !empty($interest->icon) &&
            Storage::disk('public')->exists($interest->icon)
        ) {
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

    public function show($interestId)
    {
        $interestName = Intrest::where('id', $interestId)->value('name');
    
        /*
        --------------------------------
        FETCH CATEGORIES + LISTS + ITEMS
        --------------------------------
        */
    
        $categories = CatalogCategory::with([
                'lists',
                'lists.items' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
            ->where('interest_id', $interestId)
            ->where('status', 1)
            ->get();
    
        /*
        --------------------------------
        STRUCTURE DATA
        --------------------------------
        */
    
        $data = $categories->map(function ($category) {
            return [
                'category_name' => $category->name,
                'lists' => $category->lists->map(function ($list) {
                    return [
                        'id' => $list->id,
                        'list_title' => $list->title,
                        'items' => $list->items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->custom_item_name
                                    ?? optional($item->catalogItem)->name
                                    ?? 'Item #' . $item->catalog_item_id,
                                'custom_text' => $item->custom_text,
                                'user_positions' => $item->user_positions ?: [],
                                'position_updated_count' => $item->position_updated_count ?? 0,
                            ];
                        })
                    ];
                })
            ];
        });
    
        /*
        --------------------------------
        TOTAL REORDER COUNT
        --------------------------------
        */
    
        $listIds = $categories->flatMap(function ($c) {
            return $c->lists->pluck('id');
        })->filter()->values()->all();
    
        $totalReorders = 0;
    
        if (!empty($listIds)) {
            $totalReorders = ListItem::whereIn('list_id', $listIds)
                ->sum('position_updated_count');
        }
    
        /*
        --------------------------------
        USER MAP (FIXED)
        --------------------------------
        */
    
        $userMap = User::select('id', 'full_name', 'email')
            ->get()
            ->keyBy('id');
    
        /*
        --------------------------------
        RESPONSE
        --------------------------------
        */
    
        return view('admin.interest-preferences', [
            'interestId' => $interestId,
            'interestName' => $interestName,
            'data' => $data,
            'totalReorders' => $totalReorders,
            'userMap' => $userMap,
        ]);
    }
}