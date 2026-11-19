
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Kasir - QResto</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #777;
        }

        .success {
            padding: 14px;
            margin-bottom: 20px;
            background: #d1fae5;
            color: #166534;
            border-radius: 9px;
        }

        .scanner-box {
            background: white;
            padding: 22px;
            margin-bottom: 25px;
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .scan-button {
            padding: 13px 20px;
            border: 0;
            border-radius: 8px;
            background: #16834a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .stop-button {
            padding: 13px 20px;
            border: 0;
            border-radius: 8px;
            background: #dc2626;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: none;
        }

        #reader {
            width: 100%;
            max-width: 420px;
            margin: 20px auto 0;
            display: none;
        }

        #scan-message {
            margin-top: 15px;
            font-weight: bold;
        }

        .order-card {
            background: white;
            padding: 22px;
            margin-bottom: 18px;
            border-radius: 13px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
            transition: .3s;
        }

        .order-card.highlight {
            border: 3px solid #16834a;
            background: #f0fdf4;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .badge {
            background: #fef3c7;
            color: #92400e;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-size: 19px;
            font-weight: bold;
            margin-top: 18px;
        }

        .confirm-button {
            width: 100%;
            margin-top: 20px;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #16834a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .empty {
            background: white;
            padding: 35px;
            border-radius: 12px;
            text-align: center;
            color: #777;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>QResto - Pesanan Kasir</h1>
        <p>Scan QR Code pelanggan dan konfirmasi pembayaran.</p>
    </div>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="padding:14px; margin-bottom:20px; background:#fee2e2; color:#991b1b; border-radius:9px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- FITUR SCAN QR CODE -->
    <div class="scanner-box">

        <h2>Scan QR Code Pesanan</h2>

        <p>
            Arahkan kamera ke QR Code yang ditunjukkan pelanggan.
        </p>

        <button type="button" class="scan-button" id="start-button"
                onclick="startScanner()">
            Scan QR Code
        </button>

        <button type="button" class="stop-button" id="stop-button"
                onclick="stopScanner()">
            Tutup Kamera
        </button>

        <div id="reader"></div>

        <div id="scan-message"></div>

    </div>

    <h2>Daftar Pesanan Menunggu Pembayaran</h2>

    @forelse ($orders as $order)

        <div class="order-card"
             data-order-code="{{ $order->order_code }}">

            <div class="order-header">
                <div>
                    <h3>{{ $order->order_code }}</h3>
                    <p>
                        Meja:
                        <strong>{{ $order->table?->name ?? 'Takeaway' }}</strong>
                    </p>
                </div>

                <span class="badge">
                    Menunggu Pembayaran
                </span>
            </div>

            <h4>Detail Pesanan</h4>

            @foreach ($order->items as $item)
                <div class="item">
                    <div>
                        <strong>{{ $item->menu_name }}</strong>
                        <br>
                        <small>
                            {{ $item->quantity }} x
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </small>
                    </div>

                    <strong>
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                    </strong>
                </div>
            @endforeach

            <div class="total">
                <span>Total Pembayaran</span>
                <span>
                    Rp{{ number_format($order->total, 0, ',', '.') }}
                </span>
            </div>

            <form
                action="{{ route('cashier.orders.confirm-payment', $order->id) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin mengonfirmasi pembayaran ini?')">

                @csrf

                <button type="submit" class="confirm-button">
                    Konfirmasi Pembayaran
                </button>
            </form>

        </div>

    @empty

        <div class="empty">
            <h3>Belum Ada Pesanan</h3>
            <p>Pesanan yang menunggu pembayaran akan muncul di sini.</p>
        </div>

    @endforelse

</div>

<script>
    let html5QrCode = null;
    let scannerRunning = false;

    async function startScanner() {
        const reader = document.getElementById('reader');
        const message = document.getElementById('scan-message');

        reader.style.display = 'block';
        message.innerText = 'Meminta izin kamera...';

        document.getElementById('start-button').style.display = 'none';
        document.getElementById('stop-button').style.display = 'inline-block';

        try {
            html5QrCode = new Html5Qrcode("reader");

            await html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                async function(decodedText) {
                    if (!scannerRunning && html5QrCode === null) return;

                    scannerRunning = false;

                    await stopScanner();

                    const scannedCode = decodedText.trim();

                    const cards = document.querySelectorAll('.order-card');

                    let foundOrder = null;

                    cards.forEach(card => {
                        card.classList.remove('highlight');

                        if (card.dataset.orderCode === scannedCode) {
                            foundOrder = card;
                        }
                    });

                    if (foundOrder) {
                        foundOrder.classList.add('highlight');

                        foundOrder.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        message.style.color = '#16834a';
                        message.innerText =
                            'QR Code berhasil ditemukan! Periksa detail pesanan di bawah.';
                    } else {
                        message.style.color = '#dc2626';
                        message.innerText =
                            'Pesanan tidak ditemukan dalam daftar pembayaran. Mungkin sudah dibayar atau kode tidak sesuai.';
                    }
                },
                function(errorMessage) {
                    // Proses pemindaian berlangsung.
                }
            );

            scannerRunning = true;
            message.innerText = 'Kamera aktif. Silakan scan QR Code pelanggan.';

        } catch (error) {
            message.style.color = '#dc2626';
            message.innerText = 'Kamera gagal dibuka. Periksa izin kamera browser.';

            reader.style.display = 'none';
            document.getElementById('start-button').style.display = 'inline-block';
            document.getElementById('stop-button').style.display = 'none';
        }
    }

    async function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            await html5QrCode.stop();
            html5QrCode.clear();
        }

        html5QrCode = null;
        scannerRunning = false;

        document.getElementById('reader').style.display = 'none';
        document.getElementById('start-button').style.display = 'inline-block';
        document.getElementById('stop-button').style.display = 'none';
    }
</script>

</body>
</html>
