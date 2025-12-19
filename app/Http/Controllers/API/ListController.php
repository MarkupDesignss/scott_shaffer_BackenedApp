<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ListModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

use function PHPUnit\Framework\isEmpty;

class ListController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            // dd($user);
            $lists = Auth::user()
                ->lists()
                ->whereNull('deleted_at')
                ->with([
                    'items' => function ($q) {
                        $q->with('catalogItem');
                    }
                ])
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'User lists with items retrieved successfully',
                'data'    => $lists
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:80',
                'category_id' => 'required|exists:catalog_categories,id',
                'list_size'   => 'nullable|integer|min:1|max:20',
                'visibility'  => ['nullable', Rule::in(['private', 'public'])],
            ]);

            $list = ListModel::create([
                'user_id'     => Auth::id(),
                'title'       => $validated['title'],
                'category_id' => $validated['category_id'],
                'list_size'   => $validated['list_size'] ?? 5,
                'visibility'  => $validated['visibility'] ?? 'private',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'List created successfully',
                'data'    => $list
            ], 201);
        } catch (Throwable $e) {
            return $this->serverError($e);
        }
    }
    public function show($id)
    {
        try {
            $list = ListModel::findOrFail($id);

            $this->authorizeList($list);

            return response()->json([
                'success' => true,
                'message' => 'List retrieved successfully',
                'data'    => $list->load('items.catalogItem')
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'List not found'
            ], 200);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $list = ListModel::with('items.catalogItem')->find($id);
            $this->authorizeList($list);

            $validated = $request->validate([
                'title'      => 'sometimes|string|max:80',
                'status'     => ['sometimes', Rule::in(['draft', 'published'])],
                'visibility' => ['sometimes', Rule::in(['private', 'public'])],
            ]);

            $list->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'List updated successfully',
                'data'    => $list->load('items.catalogItem')
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $list = ListModel::findOrFail($id);

            $this->authorizeList($list);

            $list->delete();

            return response()->json([
                'success' => true,
                'message' => 'List deleted successfully'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'List not found'
            ], 200);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }


    /* =========================
       Helper Methods
    ========================== */

    private function authorizeList(ListModel $list)
    {
        if ($list->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to access this list');
        }
    }

    private function handleException(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
                "reason" => $e->getMessage()
            ], 404);
        }

        return $this->serverError($e);
    }

    private function serverError(Throwable $e)
    {
        logger()->error($e);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again later.',
            'reason' => $e->getMessage()
        ], 500);
    }
}
