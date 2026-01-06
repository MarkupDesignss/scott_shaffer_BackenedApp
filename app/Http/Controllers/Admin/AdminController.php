<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPasswordOtp;
use App\Mail\AdminResetOtpMail;
use App\Models\CatalogCategory;
use App\Models\CatalogItem;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FeaturedList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'users'   => User::count(),
            'featurelist' => FeaturedList::count(),
            'totalCategories' => CatalogCategory::count(),
            'totalItems'    => CatalogItem::count(),
        ];
        return view('admin.dashboard', $data);
    }
    /**
     * Show admin login page
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ]);
    }

    /**
     * Admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function forgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors(['email' => 'Admin not found']);
        }

        $otp = random_int(100000, 999999);

        AdminPasswordOtp::updateOrCreate(
            ['email' => $request->email],
            [
                // 'otp' => Hash::make($otp),
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );


        Mail::to($request->email)->send(new AdminResetOtpMail($otp));

        return redirect()->route('admin.otp.form')
            ->with('email', $request->email)
            ->with('success', 'OTP sent to your email');
    }

    /* ==========================
       VERIFY OTP
    ========================== */

    public function otpForm()
    {
        return view('admin.auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $record = AdminPasswordOtp::where('email', $request->email)->first();

        if (!$record || Carbon::now()->gt($record->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired or invalid']);
        }

        if ($request->otp != $record->otp) {
            return back()->withErrors(['otp' => 'Incorrect OTP']);
        }
        // if (!Hash::check($request->otp, $record->otp)) {
        //     return back()->withErrors(['otp' => 'Incorrect OTP']);
        // }

        // ✅ Store verified email in session
        session(['admin_reset_email' => $request->email]);

        return redirect()->route('admin.reset.form');
    }


    /* ==========================
       RESET PASSWORD
    ========================== */

    public function resetPasswordForm()
    {
        return view('admin.auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('admin_reset_email');

        if (!$email) {
            return redirect()->route('admin.login')
                ->withErrors(['error' => 'Session expired. Please try again.']);
        }

        Admin::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        AdminPasswordOtp::where('email', $email)->delete();

        // ✅ Clear session
        session()->forget('admin_reset_email');

        return redirect()->route('admin.login')
            ->with('success', 'Password reset successfully');
    }
    
        public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:6',
        ]);

        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
