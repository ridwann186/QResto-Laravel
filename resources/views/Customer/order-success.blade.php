
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - QResto</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

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
            max-width: 520px;
            margin: 30px auto;
            background: white;
            padding: 24px;
            border-radius: 16px;
        }

        .success {
            text-align: center;
            color: #16834a;
        }

        .code {
            background: #f2f2f2;
            padding: 14px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 20px;
            overflow-wrap: anywhere;
        }

        .qr-section {
            text-align: center;
            margin: 24px 0;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 14px;
        }

        #orderQr {
            display: flex;
            justify-content: center;
            margin: 16px auto;
        }

        #orderQr img,
        #orderQr canvas {
            margin: auto;
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
        }

        .status-button {
            display: block;
            text-align: center;
            margin-top: 16px;
            padding: 14px;
            background: #16834a;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="success">
            <h1>Pesanan Berhasil!</h1>
            <p>Pesanan kamu sudah masuk ke sistem QResto.</p>
        </div>

        <p>Kode pesanan:</p>
        <div class="code">{{ $order->order_code }}</div>

        <!-- QR CODE PESANAN -->
        <div class="qr-section">
            <h3>QR Code Pesanan</h3>

            <p>Tunjukkan QR Code ini kepada kasir saat melakukan pembayaran.</p>

            <div id="orderQr"></div>

            <small>QR Code khusus untuk pesanan kamu.</small>
        </div>

        <p>Meja: <strong>{{ $order->table?->name ?? 'Takeaway' }}</strong></p>

        <p>Status pembayaran: <strong>Belum dibayar</strong></p>

        <h3>Detail pesanan</h3>

        @foreach ($order->items as $item)
            <div class="item">
                <div>
                    {{ $item->menu_name }}
                    <br>
                    <small>
                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                    </small>
                </div>

                <strong>
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </strong>
            </div>
        @endforeach

        <div class="total">
            <span>Total</span>
            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>

        <p style="text-align:center; margin-top:24px;">
            Silakan lakukan pembayaran kepada kasir.
        </p>

        <a href="{{ route('customer.order-status', $order->order_code) }}"
           class="status-button">
            Pantau Status Pesanan
        </a>

    </div>

    <script>
        const orderCode = @json($order->order_code);

        new QRCode(document.getElementById("orderQr"), {
            text: orderCode,
            width: 190,
            height: 190,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>
