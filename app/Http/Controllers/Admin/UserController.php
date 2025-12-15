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
        $data['users'] = User::select(
            'id',
            'full_name',
            'phone',
            'email',
            'country',
            'status',
            'created_at'
        )
            ->orderBy('id', 'desc')
            ->paginate(10);

        // This month users count
        $data['thisMonthUsers'] = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('admin.user.index', compact('data'));
    }
}
