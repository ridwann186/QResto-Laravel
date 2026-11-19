<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TableController extends Controller
{
    // Menampilkan daftar meja
    public function index()
    {
        $tables = RestaurantTable::orderBy('code')->get();

        return view('admin.tables', compact('tables'));
    }

    // Menampilkan formulir tambah meja
    public function create()
    {
        return view('admin.tables-create');
    }

    // Menyimpan meja baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:restaurant_tables,code'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        RestaurantTable::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'qr_token' => (string) Str::uuid(),
            'status' => 'available',
        ]);

        return redirect()
            ->route('admin.tables')
            ->with('success', 'Meja berhasil ditambahkan!');
    }

    // Menampilkan formulir edit meja
    public function edit(RestaurantTable $table)
    {
        return view('admin.tables-edit', compact('table'));
    }

    // Memperbarui data meja
    public function update(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('restaurant_tables', 'code')->ignore($table->id),
            ],
            'name' => ['required', 'string', 'max:100'],
        ]);

        $table->update($validated);

        return redirect()
            ->route('admin.tables')
            ->with('success', 'Data meja berhasil diperbarui!');
    }

    // Menghapus meja
    public function destroy(RestaurantTable $table)
    {
        if ($table->status !== 'available') {
            return back()->with('error', 'Meja yang sedang digunakan tidak dapat dihapus!');
        }

        if ($table->orders()->exists()) {
            return back()->with('error', 'Meja yang memiliki riwayat pesanan tidak dapat dihapus!');
        }

        $table->delete();

        return redirect()
            ->route('admin.tables')
            ->with('success', 'Meja berhasil dihapus!');
    }
}
