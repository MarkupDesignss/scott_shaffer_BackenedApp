<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Invalid email or password');
        }

        // Store admin session
        session(['admin_id' => $admin->id]);

        return redirect('/admin/dashboard');
    }
}
