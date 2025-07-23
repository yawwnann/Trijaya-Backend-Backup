<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user->addresses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $data = $request->validate([
                'label' => 'required|string|max:50',
                'recipient_name' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'province' => 'required|string',
                'city' => 'required|string',
                'district' => 'required|string',
                'postal_code' => 'required|string',
                'is_default' => 'boolean',
                'notes' => 'nullable|string',
                'regency_id' => 'nullable|string',
            ]);

            // Set regency_id to city value if not provided
            if (!isset($data['regency_id'])) {
                $data['regency_id'] = $data['city'];
            }

            if ($request->input('is_default')) {
                Address::where('user_id', $user->id)->update(['is_default' => false]);
            }
            $data['user_id'] = $user->id;
            $address = Address::create($data);
            return response()->json($address, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create address'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $address = Address::where('user_id', $user->id)->findOrFail($id);
        $data = $request->validate([
            'label' => 'sometimes|string|max:50',
            'recipient_name' => 'sometimes|string|max:100',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'province' => 'sometimes|string',
            'city' => 'sometimes|string',
            'district' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
            'is_default' => 'boolean',
            'notes' => 'nullable|string',
            'regency_id' => 'nullable|string',
        ]);

        // Set regency_id to city value if not provided
        if (isset($data['city']) && !isset($data['regency_id'])) {
            $data['regency_id'] = $data['city'];
        }

        if ($request->input('is_default')) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }
        $address->update($data);
        return response()->json($address);
    }

    public function destroy(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $address = Address::where('user_id', $user->id)->findOrFail($id);
        $address->delete();
        return response()->json(['success' => true]);
    }
}