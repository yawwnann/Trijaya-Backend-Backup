<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\UserProfile;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    // Mengambil profil pengguna yang sedang login
    public function show()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $profile = $user->profile;

            if (!$profile) {
                return response()->json([
                    'phone' => null,
                    'bio' => null,
                    'avatar' => null,
                    'gender' => null,
                    'birth_date' => null,
                ]);
            }

            // Sanitize output and avoid casting exceptions
            $rawBirthDate = $profile->getRawOriginal('birth_date');
            $birthDate = null;
            if (!empty($rawBirthDate)) {
                try {
                    $birthDate = Carbon::parse($rawBirthDate)->toDateString();
                } catch (\Throwable $e) {
                    $birthDate = null;
                }
            }

            return response()->json([
                'phone' => $profile->phone,
                'bio' => $profile->bio,
                'avatar' => $profile->avatar,
                'gender' => $profile->gender,
                'birth_date' => $birthDate,
            ]);
        } catch (TokenExpiredException | TokenInvalidException | JWTException $e) {
            return response()->json(['error' => 'Authentication failed'], 401);
        } catch (\Throwable $e) {
            // Do not misreport non-auth errors as 401
            return response()->json(['message' => 'Failed to fetch profile'], 500);
        }
    }

    // Membuat atau memperbarui profil pengguna
    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|string|url',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'birth_date' => 'nullable|date',
        ]);

        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['phone', 'bio', 'avatar', 'gender', 'birth_date'])
        );

        return response()->json($profile);
    }
}