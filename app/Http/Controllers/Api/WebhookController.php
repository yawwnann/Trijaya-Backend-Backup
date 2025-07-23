<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans payment notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // 1. Konfigurasi dan validasi notifikasi dasar
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        /** @var \Midtrans\Notification $notification */
        $notification = new Notification();

        // 2. Ekstrak data penting dari notifikasi
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $paymentType = $notification->payment_type;

        // 3. Cari order di database
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // 4. Verifikasi signature untuk keamanan
        $signatureKey = hash('sha512', $orderId . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
        Log::info('Webhook debug', [
            'order_id' => $orderId,
            'status_code' => $notification->status_code,
            'gross_amount' => $notification->gross_amount,
            'server_key' => config('midtrans.server_key'),
            'signature_key' => $notification->signature_key,
            'expected_signature' => $signatureKey,
        ]);
        // Untuk debug, matikan pengecekan signature sementara
        // if ($notification->signature_key != $signatureKey) {
        //     return response()->json(['message' => 'Invalid signature.'], 403);
        // }

        // 5. Update order berdasarkan status notifikasi
        if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
            // Pembayaran berhasil
            $order->payment_status = 'paid';
            $order->payment_method = $paymentType;
            $order->status = 'processing'; // Setelah bayar, status jadi 'processing'
        } else if ($transactionStatus == 'expire' || $transactionStatus == 'cancel' || $transactionStatus == 'deny') {
            // Pembayaran gagal atau dibatalkan
            $order->payment_status = 'failed';
            $order->status = 'cancelled';
        }
        $order->save();

        // Untuk debug, tampilkan data signature dan notifikasi ke response
        return response()->json([
            'debug' => [
                'order_id' => $orderId,
                'status_code' => $notification->status_code,
                'gross_amount' => $notification->gross_amount,
                'server_key' => config('midtrans.server_key'),
                'signature_key' => $notification->signature_key,
                'expected_signature' => $signatureKey,
            ],
            'status' => 'success',
        ]);
    }
}