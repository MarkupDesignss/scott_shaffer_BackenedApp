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
    public function checkUserStatus(Request $request)
    {
        $validated = $request->validate([
            'country_code' => 'required|string|max:5',
            'phone'        => 'required|string',
        ]);

        $finalPhone = $this->formatFullPhone(
            $validated['country_code'],
            $validated['phone']
        );

        $user = User::where('phone', $finalPhone)->first();

        if (!$user) {
            return response()->json([
                'success' => true,
                'exists'  => false,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'exists'  => true,
            'data'    => [
                'user_id'     => $user->id,
                'is_consent'  => (bool) $user->is_consent_completed,
                'is_interest' => (bool) $user->is_interest_completed,
                'is_profile'  => (bool) $user->is_profile_completed,
            ],
        ], 200);
    }



    public function signup(Request $request)
    {
        try {
            // 1️⃣ Validate input
            $validated = $request->validate([
                'full_name'    => 'required|string|max:255',
                'email'        => 'required|email|max:255',
                'country_code' => 'required|string|max:5',
                'phone'        => 'required|string|max:20',
                'country'      => 'required|string|max:100',
            ]);

            // 2️⃣ Format phone to +91XXXXXXXXXX
            $finalPhone = $this->formatFullPhone(
                $validated['country_code'],
                $validated['phone']
            );

            // 3️⃣ Check existing user by phone
            $userByPhone = User::where('phone', $finalPhone)->first();

            if ($userByPhone) {
                return response()->json([
                    'success' => false,
                    'exists'  => true,
                    'message' => 'Phone number already registered',
                    'data'    => [
                        'user_id'     => $userByPhone->id,
                        'is_consent'  => (bool) $userByPhone->is_consent_completed,
                        'is_interest' => (bool) $userByPhone->is_interest_completed,
                        'is_profile'  => (bool) $userByPhone->is_profile_completed,
                    ]
                ], 200);
            }

            // 4️⃣ Check existing user by email
            $userByEmail = User::where('email', $validated['email'])->first();

            if ($userByEmail) {
                return response()->json([
                    'success' => false,
                    'exists'  => true,
                    'message' => 'Email already registered',
                    'data'    => [
                        'user_id'     => $userByEmail->id,
                        'is_consent'  => (bool) $userByEmail->is_consent_completed,
                        'is_interest' => (bool) $userByEmail->is_interest_completed,
                        'is_profile'  => (bool) $userByEmail->is_profile_completed,
                    ]
                ], 200);
            }

            // 5️⃣ Create new user
            $user = User::create([
                'full_name'             => $validated['full_name'],
                'email'                 => $validated['email'],
                'country_code'          => $validated['country_code'],
                'country'               => $validated['country'],
                'phone'                 => $finalPhone,

                // onboarding flags
                'is_consent_completed'  => false,
                'is_interest_completed' => false,
                'is_profile_completed'  => false,
            ]);

            // 6️⃣ Success response
            return response()->json([
                'success' => true,
                'exists'  => false,
                'message' => 'Signup successful',
                'data'    => [
                    'user_id' => $user->id
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
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
                    'message' => 'User not found',
                ], 404);
            }

            /* ---------------------------------
         | SEND OTP (NO ONBOARDING CHECK)
         |----------------------------------*/
            $otp = random_int(100000, 999999);

            $user->update([
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'otp'     => $otp, // testing only
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error'   => $th->getMessage()
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

            /* ---------------------------------
         | BLOCK LOGIN IF ONBOARDING INCOMPLETE
         |----------------------------------*/
            if (
                !$user->is_consent_completed ||
                !$user->is_interest_completed ||
                !$user->is_profile_completed
            ) {
                return response()->json([
                    'success' => true,
                    'user_id' => $user->id,
                    'message' => 'Onboarding incomplete',
                    'data'    => [
                        'is_consent'  => (bool) $user->is_consent_completed,
                        'is_interest' => (bool) $user->is_interest_completed,
                        'is_profile'  => (bool) $user->is_profile_completed,
                    ],
                ], 200);
            }

            /* ---------------------------------
         | VERIFY & LOGIN
         |----------------------------------*/
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
            ], 200);
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
