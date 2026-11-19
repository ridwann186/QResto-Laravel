
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - QResto</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 25px;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 7px;
        }

        .header p {
            color: #777;
            margin: 0;
        }

        .button {
            display: inline-block;
            padding: 12px 16px;
            border: 0;
            border-radius: 8px;
            background: #f5a623;
            color: #222;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .back-button {
    background: #222;
    color: white;
}

        .success {
            background: #d1fae5;
            color: #166534;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table-wrapper {
            background: white;
            border-radius: 12px;
            padding: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #fafafa;
        }

        .menu-image {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
        }

        .no-image {
            width: 65px;
            height: 65px;
            background: #fff1d6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .available {
            background: #dcfce7;
            color: #166534;
        }

        .unavailable {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 7px;
        }

        .edit {
            background: #2563eb;
            color: white;
        }

        .delete {
            background: #dc2626;
            color: white;
        }

        .empty {
            text-align: center;
            color: #888;
            padding: 30px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .header h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
    <div>
        <h1>Kelola Menu</h1>
        <p>Atur daftar makanan dan minuman QResto.</p>
    </div>

    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('admin.tables') }}" class="button back-button">
            ← QR Meja
        </a>

        <a href="{{ route('admin.menus.create') }}" class="button">
            + Tambah Menu
        </a>
    </div>
</div>

        <a href="{{ route('admin.menus.create') }}" class="button">
            + Tambah Menu
        </a>
    </div>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td>
                            @if ($menu->image)
                                <img
                                    src="{{ asset('storage/' . $menu->image) }}"
                                    alt="{{ $menu->name }}"
                                    class="menu-image">
                            @else
                                <div class="no-image">🍽️</div>
                            @endif
                        </td>

                        <td>
                            <strong>{{ $menu->name }}</strong>
                        </td>

                        <td>{{ ucfirst($menu->category) }}</td>

                        <td>
                            Rp{{ number_format($menu->price, 0, ',', '.') }}
                        </td>

                        <td>
                            @if ($menu->is_available)
                                <span class="status available">
                                    Tersedia
                                </span>
                            @else
                                <span class="status unavailable">
                                    Tidak Tersedia
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">
                                <a
                                    href="{{ route('admin.menus.edit', $menu->id) }}"
                                    class="button edit">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.menus.destroy', $menu->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus menu ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="button delete">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">
                            Belum ada menu yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

</body>
</html>
