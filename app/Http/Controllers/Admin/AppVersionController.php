<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppVersionController extends Controller
{
    public function index()
    {
        $versions = DB::table('app_versions')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.app_versions.index', compact('versions'));
    }

    public function edit($id)
    {
        $version = DB::table('app_versions')->where('id', $id)->first();

        if (!$version) {
            return redirect()->route('admin.app_versions.index')
                ->with('error', 'Version record not found.');
        }

        return view('admin.app_versions.edit', compact('version'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'latest_version' => 'required|string|max:20',
            'min_required_version' => 'required|string|max:20',
            'force_update' => 'nullable',
            'android_url' => 'nullable|url',
            'ios_url' => 'nullable|url',
        ]);

        DB::table('app_versions')
            ->where('id', $id)
            ->update([
                'latest_version' => $request->latest_version,
                'min_required_version' => $request->min_required_version,
                'force_update' => $request->has('force_update') ? 1 : 0,
                'android_url' => $request->android_url,
                'ios_url' => $request->ios_url,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.app_versions.index')
            ->with('success', 'App version updated successfully.');
    }
}
