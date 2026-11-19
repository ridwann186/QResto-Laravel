
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - QResto</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 24px;
            color: #222;
        }

        .container {
            max-width: 1200px;
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
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
            color: #777;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            background: #202020;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .summary-card p {
            color: #777;
            margin: 0 0 12px;
        }

        .summary-card h2 {
            margin: 0;
            font-size: 27px;
        }

        .revenue {
            color: #16834a;
        }

        .table-container {
            background: white;
            padding: 22px;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .table-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #fafafa;
            color: #555;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            background: #d8f5df;
            color: #176b32;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #777;
        }

        .items {
            line-height: 1.7;
        }

        @media (max-width: 600px) {
            body {
                padding: 14px;
            }

            .table-container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>📊 Laporan Penjualan</h1>
            <p>Ringkasan transaksi dan pendapatan QResto.</p>
        </div>

        <a href="{{ route('admin.tables') }}" class="button">
            ← Kembali ke Admin
        </a>
    </div>

    <div class="summary">

        <div class="summary-card">
            <p>Total Transaksi Dibayar</p>
            <h2>{{ number_format($totalOrders, 0, ',', '.') }}</h2>
        </div>

        <div class="summary-card">
            <p>Total Pendapatan</p>
            <h2 class="revenue">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </h2>
        </div>

    </div>

    <div class="table-container">

        <h2>Riwayat Transaksi</h2>

        @if ($orders->isEmpty())

            <div class="empty">
                <h3>Belum Ada Transaksi</h3>
                <p>Transaksi yang sudah dibayar akan muncul di sini.</p>
            </div>

        @else

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pesanan</th>
                        <th>Meja</th>
                        <th>Detail Pesanan</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $index => $order)

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>{{ $order->order_code }}</strong>
                            </td>

                            <td>
                                {{ $order->table->name ?? 'Takeaway' }}
                            </td>

                            <td class="items">
                                @foreach ($order->items as $item)
                                    {{ $item->menu_name }}
                                    ({{ $item->quantity }}x)
                                    <br>
                                @endforeach
                            </td>

                            <td>
                                <strong>
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                <span class="badge">LUNAS</span>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>

        @endif

    </div>

</div>

</body>
</html>
