
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dapur - QResto</title>

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
            max-width: 1100px;
            margin: auto;
        }

        .header {
            background: #202020;
            color: white;
            padding: 24px;
            border-radius: 14px;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
            color: #ddd;
        }

        .success-message {
            background: #d8f5df;
            color: #176b32;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .order-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .order-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .order-code {
            font-size: 17px;
            font-weight: bold;
        }

        .status {
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .processing {
            background: #fff0ce;
            color: #a56600;
        }

        .ready {
            background: #d8f5df;
            color: #176b32;
        }

        .table-info {
            color: #666;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .item-list {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 12px 0;
            margin-bottom: 16px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 7px 0;
            font-size: 14px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-quantity {
            color: #666;
            white-space: nowrap;
        }

        .notes {
            background: #fff8e9;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .ready-button {
            display: block;
            width: 100%;
            border: none;
            background: #16834a;
            color: white;
            padding: 14px;
            border-radius: 9px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .ready-button:hover {
            background: #116b3b;
        }

        .finished-info {
            text-align: center;
            background: #e9f8ed;
            color: #176b32;
            padding: 13px;
            border-radius: 9px;
            font-weight: bold;
        }

        .empty {
            background: white;
            padding: 45px 20px;
            text-align: center;
            border-radius: 14px;
            color: #777;
        }

        @media (max-width: 600px) {
            body {
                padding: 14px;
            }

            .header {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🍳 Dapur QResto</h1>
        <p>Kelola dan pantau pesanan yang masuk ke dapur.</p>
    </div>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if ($orders->isEmpty())

        <div class="empty">
            <h2>Belum Ada Pesanan</h2>
            <p>Pesanan yang sudah dibayar kasir akan muncul di sini.</p>
        </div>

    @else

        <div class="order-list">

            @foreach ($orders as $order)

                <div class="order-card">

                    <div class="order-header">
                        <div class="order-code">
                            {{ $order->order_code }}
                        </div>

                        @if ($order->status === 'processing')
                            <span class="status processing">
                                SEDANG DIPROSES
                            </span>
                        @else
                            <span class="status ready">
                                SIAP DISAJIKAN
                            </span>
                        @endif
                    </div>

                    <div class="table-info">
                        Meja:
                        <strong>
                            {{ $order->table?->name ?? 'Takeaway' }}
                        </strong>
                        <br>

                        Waktu:
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </div>

                    <div class="item-list">

                        @foreach ($order->items as $item)

                            <div class="item">
                                <span class="item-name">
                                    {{ $item->menu_name }}
                                </span>

                                <span class="item-quantity">
                                    x {{ $item->quantity }}
                                </span>
                            </div>

                        @endforeach

                    </div>

                    @if ($order->status === 'processing')

                        <form
                            action="{{ route('kitchen.orders.ready', $order->id) }}"
                            method="POST"
                        >
                            @csrf

                            <button type="submit" class="ready-button">
                                ✓ Tandai Siap Disajikan
                            </button>
                        </form>

                    @else

                        <div class="finished-info">
                            ✓ Pesanan Siap Disajikan
                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    @endif

</div>

</body>
</html>
