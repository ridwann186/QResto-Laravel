<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenOrderController extends Controller
{
    // Menampilkan pesanan yang sudah dibayar
    public function index()
    {
        $orders = Order::with(['items', 'table'])
            ->where('payment_status', 'paid')
            ->whereIn('status', ['processing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kitchen.orders.index', compact('orders'));
    }

    // Mengubah status pesanan menjadi siap disajikan
    public function markReady(Order $order)
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $lockedOrder->payment_status === 'paid' &&
                $lockedOrder->status === 'processing'
            ) {
                $lockedOrder->update([
                    'status' => 'ready',
                ]);
            }
        });

        return redirect()->route('kitchen.orders.index')
            ->with('success', 'Pesanan berhasil ditandai siap disajikan!');
    }
}
