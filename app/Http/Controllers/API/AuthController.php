<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create full phone number
     */
    private function formatFullPhone($countryCode, $phone)
    {
        return $countryCode . preg_replace('/\D/', '', $phone);
    }

    /**
     * Signup (API)
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

        $finalPhone = $this->formatFullPhone(
            $validated['country_code'],
            $validated['phone']
        );

        if (User::where('phone', $finalPhone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone already registered',
            ], 409);
        }

        $user = User::create([
            'full_name'    => $validated['full_name'],
            'email'        => $validated['email'],
            'country_code' => $validated['country_code'],
            'country'      => $validated['country'],
            'phone'        => $finalPhone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Signup successful',
            'user'    => $user
        ]);
    }

    /**
     * Request OTP
     */
    public function requestOtp(Request $request)
    {
        $validated = $request->validate([
            'phone'   => 'required|string',
            'country' => 'required|string',
        ]);

        $user = User::where('phone', $validated['phone'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $otp = random_int(100000, 999999);

        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent',
            'otp'     => $otp // testing only
        ]);
    }

    /**
     * Verify OTP & Login (API)
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|numeric',
        ]);

        $user = User::where('phone', $validated['phone'])->first();

        if (!$user || $user->otp != $validated['otp']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        $user->update([
            'otp'               => null,
            'is_phone_verified' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * Google Login (API)
     */
    public function googleLogin(Request $request)
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'full_name' => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'provider'  => 'google',
                'password'  => bcrypt(Str::random(32)),
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Google login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * Apple Login (API)
     */
    public function appleLogin(Request $request)
    {
        $appleUser = Socialite::driver('apple')
            ->stateless()
            ->user();

        $user = User::where('apple_id', $appleUser->getId())->first();

        if (!$user && $appleUser->getEmail()) {
            $user = User::where('email', $appleUser->getEmail())->first();
        }

        if (!$user) {
            $user = User::create([
                'full_name' => $appleUser->getName() ?? 'Apple User',
                'email'     => $appleUser->getEmail(),
                'apple_id'  => $appleUser->getId(),
                'provider'  => 'apple',
                'password'  => bcrypt(Str::random(32)),
            ]);
        } elseif (!$user->apple_id) {
            $user->update([
                'apple_id' => $appleUser->getId(),
                'provider' => 'apple',
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Apple login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * Logout (API)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Delete Account
     */
    public function deleteAccount(Request $request)
    {
        $request->user()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    }
}