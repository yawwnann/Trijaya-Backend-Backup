<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RajaOngkirService;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShippingController extends Controller
{
    private $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    /**
     * Hitung ongkir untuk keranjang belanja
     */
    public function calculateShipping(Request $request)
    {
        // Validasi dan autentikasi tetap boleh dipakai jika perlu
        $staticCosts = [
            ["name" => "Nusantara Card Semesta", "code" => "ncs", "service" => "DARAT", "description" => "Regular Darat", "cost" => 8000, "etd" => "6-7 day"],
            ["name" => "Nusantara Card Semesta", "code" => "ncs", "service" => "NRS", "description" => "Regular Service", "cost" => 13000, "etd" => "5-6 day"],
            ["name" => "Lion Parcel", "code" => "lion", "service" => "JAGOPACK", "description" => "Economy Service", "cost" => 13500, "etd" => "3-6 day"],
            ["name" => "Lion Parcel", "code" => "lion", "service" => "REGPACK", "description" => "Regular Service", "cost" => 14000, "etd" => "3-4 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "ECO", "description" => "Economy Service", "cost" => 15000, "etd" => "5 day"],
            ["name" => "ID Express", "code" => "ide", "service" => "STD", "description" => "Std", "cost" => 15600, "etd" => "0-0 day"],
            ["name" => "SiCepat Express", "code" => "sicepat", "service" => "REG", "description" => "Reguler", "cost" => 15900, "etd" => "3-5 day"],
            ["name" => "J&T Express", "code" => "jnt", "service" => "EZ", "description" => "Reguler", "cost" => 16000, "etd" => ""],
            ["name" => "Lion Parcel", "code" => "lion", "service" => "BOSSPACK", "description" => "Priority Service", "cost" => 17000, "etd" => "1-2 day"],
            ["name" => "Royal Express Indonesia (REX)", "code" => "rex", "service" => "REG", "description" => "Regular", "cost" => 17000, "etd" => "7-7 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "DAT", "description" => "Tiki Daun", "cost" => 18000, "etd" => "3 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "REG", "description" => "Reguler Service", "cost" => 18000, "etd" => "3 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "SRP", "description" => "Tiki Sirip", "cost" => 18000, "etd" => "3 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "TRX", "description" => "Tiki Tirex", "cost" => 18000, "etd" => "3 day"],
            ["name" => "Jalur Nugraha Ekakurir (JNE)", "code" => "jne", "service" => "REG", "description" => "Layanan Reguler", "cost" => 18000, "etd" => "6 day"],
            ["name" => "AnterAja", "code" => "anteraja", "service" => "REG", "description" => "Anteraja Regular", "cost" => 20000, "etd" => "2-4 day"],
            ["name" => "Satria Antaran Prima", "code" => "sap", "service" => "UDRREG", "description" => "Reguler", "cost" => 21000, "etd" => "2-4 day"],
            ["name" => "Royal Express Indonesia (REX)", "code" => "rex", "service" => "EXP", "description" => "Express", "cost" => 25000, "etd" => "5-5 day"],
            ["name" => "Ninja Xpress", "code" => "ninja", "service" => "STANDARD", "description" => "Standard Service", "cost" => 34800, "etd" => ""],
            ["name" => "Satria Antaran Prima", "code" => "sap", "service" => "DRGREG", "description" => "Cargo", "cost" => 45000, "etd" => "3-5 day"],
            ["name" => "Lion Parcel", "code" => "lion", "service" => "BIGPACK", "description" => "Big Package Service", "cost" => 63200, "etd" => "3-6 day"],
            ["name" => "SiCepat Express", "code" => "sicepat", "service" => "GOKIL", "description" => "Cargo Per Kg (Minimal 10kg)", "cost" => 70000, "etd" => "4-8 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "TRC", "description" => "Trucking", "cost" => 75000, "etd" => "7 day"],
            ["name" => "Jalur Nugraha Ekakurir (JNE)", "code" => "jne", "service" => "JTR", "description" => "JNE Trucking", "cost" => 90000, "etd" => "5 day"],
            ["name" => "Jalur Nugraha Ekakurir (JNE)", "code" => "jne", "service" => "JTR<130", "description" => "JNE Trucking", "cost" => 200000, "etd" => "5 day"],
            ["name" => "Jalur Nugraha Ekakurir (JNE)", "code" => "jne", "service" => "JTR>130", "description" => "JNE Trucking", "cost" => 600000, "etd" => "5 day"],
            ["name" => "Lion Parcel", "code" => "lion", "service" => "OTOPACK150", "description" => "Otomotive Shipping Service", "cost" => 709500, "etd" => "5-8 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "T15", "description" => "Motor Di Bawah 150cc", "cost" => 880000, "etd" => "7 day"],
            ["name" => "Jalur Nugraha Ekakurir (JNE)", "code" => "jne", "service" => "JTR>200", "description" => "JNE Trucking", "cost" => 900000, "etd" => "5 day"],
            ["name" => "Lion Parcel", "code" => "lion", "service" => "OTOPACK250", "description" => "Otomotive Shipping Service", "cost" => 1000000, "etd" => "5-8 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "T25", "description" => "Motor Di Bawah 250cc", "cost" => 1140000, "etd" => "7 day"],
            ["name" => "Citra Van Titipan Kilat (TIKI)", "code" => "tiki", "service" => "T60", "description" => "Motor Di Bawah 600cc", "cost" => 1400000, "etd" => "7 day"]
        ];

        $weight = $request->weight ?? 1000; // berat dalam gram
        $weightKg = ceil($weight / 1000); // dibulatkan ke atas, minimal 1kg

        $finalCosts = [];
        foreach ($staticCosts as $cost) {
            $costPerKg = $cost['cost'];
            $costTotal = $costPerKg * $weightKg;
            $finalCosts[] = array_merge($cost, ['cost' => $costTotal]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghitung ongkir (statis)',
            'data' => [
                'weight' => $weight,
                'costs' => $finalCosts
            ]
        ]);
    }

    /**
     * Dapatkan daftar kurir yang tersedia
     */
    public function getCouriers()
    {
        $couriers = $this->rajaOngkirService->getAvailableCouriers();

        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    }

    /**
     * Update ongkir pada keranjang belanja
     */
    public function updateCartShipping(Request $request)
    {
        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_service' => 'required|string',
            'shipping_courier' => 'required|string'
        ]);

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $cart = Order::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja tidak ditemukan'
                ], 404);
            }

            // Update ongkir pada keranjang
            $cart->shipping_cost = $request->shipping_cost;
            $cart->shipping_service = $request->shipping_service;
            $cart->shipping_courier = $request->shipping_courier;

            // Hitung ulang grand total
            $total = $cart->items()->sum('subtotal');
            $cart->total_amount = $total;
            $cart->grand_total = $total + $cart->shipping_cost + $cart->tax - $cart->discount;

            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Ongkir berhasil diperbarui',
                'data' => $cart->load('items.produk')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}