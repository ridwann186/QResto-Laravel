<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CashierOrderController extends Controller
{
    // Menampilkan daftar pesanan yang perlu ditangani kasir
    public function index()
    {
        $orders = Order::with(['items', 'table'])
            ->where('payment_status', 'unpaid')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('cashier.orders.index', compact('orders'));
    }

    // Mengonfirmasi pembayaran pelanggan
    public function confirmPayment(Order $order)
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Cegah pembayaran dikonfirmasi dua kali
            if ($lockedOrder->payment_status === 'paid') {
                return;
            }

            $lockedOrder->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);
        });

        return redirect()
            ->route('cashier.orders.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}
