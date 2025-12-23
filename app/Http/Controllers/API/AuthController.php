<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use MessageFormatter;
use Illuminate\Validation\ValidationException;

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
        try {
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

            // Phone already exists check
            if (User::where('phone', $finalPhone)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already registered with this phone number',
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
                'user'    => $user,
            ], 201);
        } catch (ValidationException $e) {

            // Email already exists OR other validation issues
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Signup failed',
            ], 500);
        }
    }
    /**
     * Request OTP
     */
    public function requestOtp(Request $request)
    {
        try {
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
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send otp',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify OTP & Login (API)
     */
    public function verifyOtp(Request $request)
    {
        try {
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
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Google Login (API)
     */
    public function googleLogin(Request $request)
    {
        try {

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
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to verigy google user',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Apple Login (API)
     */
    public function appleLogin(Request $request)
    {
        try {
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
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to veify Apple user',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout (API)
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to logout',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete Account
     */
    public function deleteAccount(Request $request)
    {
        try {
            $request->user()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account and related data deleted successfully'
            ]);
        } catch (\Throwable $th) {

            logger()->error($th);

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete account'
            ], 500);
        }
    }
}