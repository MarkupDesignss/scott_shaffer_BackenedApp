<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // GET: /api/profile
    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => $user
        ]);
    }

    // PUT: /api/profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name'    => 'nullable|string|max:255',
            'email'        => 'nullable|email|unique:users,email,' . $request->user()->id,
            'country'      => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:5',
            'phone'        => 'nullable|string|unique:users,phone,' . $request->user()->id,
        ]);

        $user = $request->user();

        $user->update($request->only([
            'full_name',
            'email',
            'country',
            'country_code',
            'phone'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => $user
        ]);
    }
}
