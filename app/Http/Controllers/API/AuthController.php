<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create full phone number: +91 + 9876543210 = +919876543210
     */
    private function formatFullPhone($countryCode, $phone)
    {
        return $countryCode . preg_replace('/\D/', '', $phone);
    }

    /**
     * Signup
     */
    public function signup(Request $request)
    {
        $validated = $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'country_code' => 'required|string|max:5',
            'phone'        => 'required|string',
            'country'      => 'required|string|max:100',
        ]);

        // Combine phone
        $finalPhone = $this->formatFullPhone($validated['country_code'], $validated['phone']);

        // Check duplicate final phone
        if (User::where('phone', $finalPhone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone already registered.',
            ], 409);
        }

        $userId = User::insertGetId([
            'full_name'  => $validated['full_name'],
            'email'      => $validated['email'],
            'country_code' => $validated['country_code'],
            'country'    => $validated['country'],
            'phone'      => $finalPhone,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Signup successful.',
            'user_id' => $userId,
            'phone'   => $finalPhone
        ]);
    }

    /**
     * Login (Generate OTP)
     */
    public function requestOtp(Request $request)
    {
        $validated = $request->validate([
            // 'country_code' => 'required|string|max:5',
            'phone'        => 'required|string',
            'country'      => 'required|string|max:100',
        ]);

        // Combine phone
        $finalPhone = $validated['phone'];

        $user = User::select('id')
            ->where('phone', $finalPhone)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Generate OTP
        $otp = random_int(100000, 999999);

        User::where('id', $user->id)->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'updated_at'     => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp'     => $otp, // testing only
            'phone'   => $finalPhone
        ]);
    }

    /**
     * Verify OTP & Login
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|numeric'
        ]);

        $user = User::where('phone', $validated['phone'])
            ->first(['id', 'otp', 'otp_expires_at']);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ((int)$user->otp !== (int)$validated['otp']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 400);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired',
            ], 400);
        }

        User::where('id', $user->id)->update([
            'is_phone_verified' => true,
            'otp' => null,
            'updated_at' => now()
        ]);

        $userModel = User::find($user->id);
        $token = $userModel->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'token'   => $token,
            'user'    => $userModel
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
