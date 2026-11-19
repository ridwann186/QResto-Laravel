
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - QResto</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }

        .container {
            max-width: 560px;
            margin: 25px auto;
            padding: 25px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
            color: #16834a;
        }

        .order-code {
            padding: 15px;
            background: #f2f3f5;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .section {
            margin-top: 25px;
        }

        .status-card {
            padding: 18px;
            background: #f8f9fa;
            border-radius: 12px;
            text-align: center;
        }

        .status-card h2 {
            margin: 0 0 8px;
            color: #16834a;
        }

        .step {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin: 20px 0;
        }

        .step-number {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #e5e7eb;
            color: #555;
            font-weight: bold;
        }

        .step.active .step-number {
            background: #16834a;
            color: white;
        }

        .step p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
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
            margin-top: 18px;
            font-size: 19px;
            font-weight: bold;
        }

        .refresh-info {
            text-align: center;
            color: #777;
            font-size: 12px;
            margin-top: 20px;
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .container {
                margin: 10px auto;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <h1>QResto</h1>
        <p>Pantau perkembangan pesanan kamu</p>
    </div>

    <p>Kode pesanan:</p>
    <div class="order-code">{{ $order->order_code }}</div>

    <div class="section">
        <p>Meja: <strong>{{ $order->table?->name ?? '-' }}</strong></p>

        <div class="status-card">
            <h2 id="status-label">Memuat status...</h2>
            <p id="payment-label">Memuat pembayaran...</p>
        </div>
    </div>

    <div class="section">
        <h3>Proses pesanan</h3>

        <div class="step" data-step="waiting_payment">
            <div class="step-number">1</div>
            <div>
                <strong>Menunggu pembayaran</strong>
                <p>Pesanan menunggu konfirmasi pembayaran kasir.</p>
            </div>
        </div>

        <div class="step" data-step="processing">
            <div class="step-number">2</div>
            <div>
                <strong>Sedang diproses</strong>
                <p>Pesanan sedang disiapkan oleh dapur.</p>
            </div>
        </div>

        <div class="step" data-step="ready">
            <div class="step-number">3</div>
            <div>
                <strong>Pesanan siap</strong>
                <p>Pesanan sudah siap disajikan.</p>
            </div>
        </div>

        <div class="step" data-step="completed">
            <div class="step-number">4</div>
            <div>
                <strong>Pesanan selesai</strong>
                <p>Terima kasih sudah memesan di QResto.</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Detail pesanan</h3>

        @foreach ($order->items as $item)
            <div class="item">
                <div>
                    <strong>{{ $item->menu_name }}</strong>
                    <br>
                    <small>
                        {{ $item->quantity }} x
                        Rp {{ number_format($item->price, 0, ',', '.') }}
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
    </div>

    <p class="refresh-info" id="refresh-info">
        Status diperbarui otomatis setiap 5 detik.
    </p>

</div>

<script>
    
const statusUrl = @json(
    route('customer.order-status-data', $order->order_code)
);

const statusLabels = {
    waiting_payment: 'Menunggu Pembayaran',
    processing: 'Sedang Diproses',
    ready: 'Pesanan Siap',
    completed: 'Pesanan Selesai',
    cancelled: 'Pesanan Dibatalkan'
};

const paymentLabels = {
    unpaid: 'Pembayaran belum dikonfirmasi',
    paid: 'Pembayaran sudah dikonfirmasi'
};

const stepOrder = [
    'waiting_payment',
    'processing',
    'ready',
    'completed'
];

let statusInterval = null;

function updateStatus(data) {
    const status = data.status;

    const statusLabel = document.getElementById('status-label');
    const refreshInfo = document.getElementById('refresh-info');

    statusLabel.textContent =
        statusLabels[status] || 'Status: ' + status;

    statusLabel.style.color =
        status === 'cancelled' ? '#c0392b' : '#16834a';

    document.getElementById('payment-label').textContent =
        paymentLabels[data.payment_status] ||
        'Status pembayaran: ' + data.payment_status;

    const currentIndex = stepOrder.indexOf(status);

    document.querySelectorAll('.step').forEach(step => {
        const stepStatus = step.dataset.step;
        const stepIndex = stepOrder.indexOf(stepStatus);

        step.classList.toggle(
            'active',
            currentIndex >= 0 && stepIndex <= currentIndex
        );
    });

    // Hentikan pembaruan otomatis jika pesanan sudah berakhir
    if (status === 'completed' || status === 'cancelled') {
        clearInterval(statusInterval);

        refreshInfo.textContent = status === 'completed'
            ? 'Pesanan selesai. Terima kasih sudah memesan di QResto!'
            : 'Pesanan ini telah dibatalkan. Silakan hubungi kasir.';
    } else {
        refreshInfo.textContent =
            'Status diperbarui otomatis setiap 5 detik.';
    }
}

async function refreshStatus() {
    try {
        const response = await fetch(statusUrl, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil status pesanan');
        }

        const data = await response.json();
        updateStatus(data);

    } catch (error) {
        document.getElementById('refresh-info').textContent =
            'Status belum dapat diperbarui. Akan dicoba kembali.';
    }
}

// Ambil status pertama kali
refreshStatus();

// Perbarui status setiap 5 detik
statusInterval = setInterval(refreshStatus, 5000);

</script>

</body>
</html>
