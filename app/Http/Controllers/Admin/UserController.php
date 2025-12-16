<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    public function users()
    {
        $data['users'] = User::with('interests')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $data['thisMonthUsers'] = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.user.index', compact('data'));
    }
}