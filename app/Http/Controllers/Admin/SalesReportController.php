<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SalesReportController extends Controller
{
    // Menampilkan laporan penjualan
    public function index()
    {
        // Mengambil pesanan yang sudah dibayar
        $paidOrders = Order::where('payment_status', 'paid');

        // Menghitung jumlah transaksi
        $totalOrders = (clone $paidOrders)->count();

        // Menghitung total pendapatan
        $totalRevenue = (clone $paidOrders)->sum('total');

        // Mengambil 100 transaksi terbaru
        $orders = (clone $paidOrders)
            ->with(['items', 'table'])
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return view('admin.reports.sales', compact(
            'totalOrders',
            'totalRevenue',
            'orders'
        ));
    }
}
