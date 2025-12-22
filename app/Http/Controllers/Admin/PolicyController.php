<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::orderBy('order')->get();
        return view('admin.policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required',
            'order'       => 'nullable|integer',
            'is_active'   => 'boolean',
            'version'     => 'nullable|string|max:50',
        ]);

        $data['slug']       = Str::slug($data['name']);
        $data['created_by'] = Auth::id();

        Policy::create($data);

        return redirect()
            ->route('admin.policies.index')
            ->with('success', 'Policy created successfully');
    }

    public function edit(Policy $policy)
    {
        return view('admin.policies.edit', compact('policy'));
    }

    public function show(Policy $policy)
    {
        return view('admin.policies.show', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required',
            'order'       => 'nullable|integer',
            'is_active'   => 'boolean',
            'version'     => 'nullable|string|max:50',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $policy->update($data);

        return redirect()
            ->route('admin.policies.index')
            ->with('success', 'Policy updated successfully');
    }

    public function destroy(Policy $policy)
    {
        $policy->delete();

        return redirect()
            ->route('admin.policies.index')
            ->with('success', 'Policy deleted successfully');
    }
}
