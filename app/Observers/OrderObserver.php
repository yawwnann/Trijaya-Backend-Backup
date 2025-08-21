<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Auto-update status menjadi cancelled jika payment_status = failed
        if ($order->payment_status === 'failed' && $order->status !== 'cancelled') {
            $order->updateQuietly(['status' => 'cancelled']);
        }
    }

    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order): void
    {
        // Pastikan status tidak bisa diubah jika payment_status = failed
        if ($order->payment_status === 'failed' && $order->isDirty('status')) {
            $order->status = 'cancelled';
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
