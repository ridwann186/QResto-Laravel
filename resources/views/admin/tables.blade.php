
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Meja - QResto</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f4f5f7;
            color: #222;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h1 {
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 28px;
        }

        .table-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .table-card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }

        .qr-code {
            display: flex;
            justify-content: center;
            margin: 20px auto;
            min-height: 180px;
        }

        .status {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            background: #dcfce7;
            color: #166534;
            font-size: 13px;
        }

        .available {
    background: #dcfce7;
    color: #166534;
}

.occupied {
    background: #fee2e2;
    color: #991b1b;
}

        .url {
            display: block;
            font-size: 11px;
            color: #777;
            word-break: break-all;
            margin-top: 15px;
        }

        button {
            padding: 10px 18px;
            border: 0;
            border-radius: 8px;
            background: #222;
            color: white;
            cursor: pointer;
        }

        .admin-button {
    display: inline-block;
    padding: 12px 20px;
    background: #f5a623;
    color: #222;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    margin-bottom: 25px;
}

.admin-button:hover {
    background: #e69512;
}

        @media print {
            body {
                background: white;
            }

            .table-card {
                box-shadow: none;
                border: 1px solid #ddd;
                break-inside: avoid;
            }

            button {
                display: none;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h1>QResto - QR Meja</h1>
    <p class="subtitle">
        Kelola QR Code untuk pemesanan Dine In.
    </p>

    <a href="{{ route('admin.menus.index') }}" class="admin-button">
    + Kelola Menu
</a>

<a href="{{ route('admin.tables.create') }}" class="admin-button">
    + Tambah Meja
</a>

<a href="{{ route('admin.reports.sales') }}" class="admin-button">
    📊 Laporan Penjualan
</a>

@if (session('success'))
    <div style="background:#d1fae5;color:#166534;padding:14px;border-radius:8px;margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="background:#fee2e2;color:#991b1b;padding:14px;border-radius:8px;margin-bottom:20px;">
        {{ session('error') }}
    </div>
@endif

    <div class="table-list">

        @forelse ($tables as $table)
            <div class="table-card">

                <h2>{{ $table->name }}</h2>

                <p>ID Meja: {{ $table->code }}</p>

                <span class="status {{ $table->status === 'available' ? 'available' : 'occupied' }}">
    {{ $table->status === 'available' ? 'Tersedia' : 'Sedang Digunakan' }}
</span>

                <div
                    class="qr-code"
                    id="qr-{{ $table->id }}"
                ></div>

                <span class="url">
                    {{ route('customer.dine-in', $table->qr_token) }}
                </span>

                <br>

                <button onclick="window.print()">
                    Cetak QR
                </button>

                <a
    href="{{ route('admin.tables.edit', $table->id) }}"
    class="admin-button"
>
    ✏️ Edit Meja
</a>

<form
    action="{{ route('admin.tables.destroy', $table->id) }}"
    method="POST"
    onsubmit="return confirm('Yakin ingin menghapus meja ini?')"
    style="margin-top:10px;"
>
    @csrf
    @method('DELETE')

    <button type="submit" style="background:#dc2626;">
        🗑️ Hapus Meja
    </button>
</form>

                <script>
                    new QRCode(
                        document.getElementById("qr-{{ $table->id }}"),
                        {
                            text: @json(route('customer.dine-in', $table->qr_token)),
                            width: 180,
                            height: 180
                        }
                    );
                </script>

            </div>
        @empty
            <p>Belum ada data meja di database.</p>
        @endforelse

    </div>
</div>
</body>
</html>
