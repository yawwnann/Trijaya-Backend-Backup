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
            if (!empty($rawBirthDate) && $rawBirthDate !== '0000-00-00' && $rawBirthDate !== '1970-01-01') {
                try {
                    // Pastikan data adalah string yang valid
                    if (is_string($rawBirthDate) && strlen($rawBirthDate) > 0) {
                        $birthDate = Carbon::parse($rawBirthDate)->toDateString();
                    }
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
            'birth_date' => 'nullable|date_format:Y-m-d',
        ]);

        // Sanitasi data sebelum save
        $data = $request->only(['phone', 'bio', 'avatar', 'gender', 'birth_date']);

        // Pastikan birth_date valid sebelum save
        if (isset($data['birth_date']) && !empty($data['birth_date'])) {
            try {
                // Validasi format tanggal
                $date = Carbon::parse($data['birth_date']);
                if ($date->year < 1900 || $date->year > date('Y')) {
                    $data['birth_date'] = null; // Set null jika tahun tidak valid
                }
            } catch (\Throwable $e) {
                $data['birth_date'] = null; // Set null jika parsing gagal
            }
        }

        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return response()->json($profile);
    }
}