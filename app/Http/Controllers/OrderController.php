<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman pemesanan berdasarkan QR meja.
     */
    public function dineIn(string $qrToken)
    {
        $table = RestaurantTable::where('qr_token', $qrToken)
            ->firstOrFail();

        // Jika meja sedang digunakan, tampilkan halaman meja terisi.
        if (!$table->isAvailable()) {
            return response()->view(
                'customer.table-occupied',
                ['table' => $table],
                409
            );
        }

        // Ambil menu yang tersedia.
        $menus = Menu::where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('customer.order', [
            'orderType' => 'dine_in',
            'tableCode' => $table->code,
            'table' => $table,
            'menus' => $menus,
        ]);
    }

    /**
     * Memproses checkout pelanggan.
     */
    public function checkout(Request $request, string $qrToken)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => [
                'required',
                'integer',
                'distinct',
                'exists:menus,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        $order = DB::transaction(function () use ($validated, $qrToken) {

            // Kunci data meja agar tidak dipesan bersamaan.
            $table = RestaurantTable::where('qr_token', $qrToken)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$table->isAvailable()) {
                throw ValidationException::withMessages([
                    'table' => 'Maaf, meja ini baru saja dipesan pelanggan lain.',
                ]);
            }

            $quantities = collect($validated['items'])
                ->pluck('quantity', 'id');

            // Ambil menu yang masih tersedia.
            $menus = Menu::whereIn('id', $quantities->keys())
                ->where('is_available', true)
                ->get();

            if ($menus->count() !== $quantities->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Ada menu yang sudah tidak tersedia.',
                ]);
            }

            // Hitung total berdasarkan harga database.
            $total = 0;

            foreach ($menus as $menu) {
                $total += (float) $menu->price
                    * (int) $quantities[$menu->id];
            }

            // Buat pesanan.
            $order = Order::create([
                'order_code' => 'ORD-'
                    . now()->format('YmdHis')
                    . '-'
                    . Str::upper(Str::random(5)),

                'table_id' => $table->id,
                'order_type' => 'dine_in',
                'status' => 'waiting_payment',
                'payment_status' => 'unpaid',
                'total' => $total,
            ]);

            // Simpan detail pesanan.
            foreach ($menus as $menu) {
                $quantity = (int) $quantities[$menu->id];
                $price = (float) $menu->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                ]);
            }

            // Tandai meja sedang digunakan.
            $table->update([
                'status' => 'occupied',
            ]);

            return $order;
        });

        return redirect()->route('customer.order-success', [
            'orderCode' => $order->order_code,
        ]);
    }

    /**
     * Menampilkan halaman pesanan berhasil.
     */
    public function success(string $orderCode)
    {
        $order = Order::with(['items', 'table'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('customer.order-success', compact('order'));
    }

    /**
     * Menampilkan halaman pemantauan status pesanan.
     */
    public function status(string $orderCode)
    {
        $order = Order::with(['items', 'table'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('customer.order-status', compact('order'));
    }

    /**
     * Mengirim data status pesanan untuk pembaruan otomatis.
     */
    public function statusData(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->firstOrFail();

        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'updated_at' => $order->updated_at->toDateTimeString(),
        ]);
    }
}
