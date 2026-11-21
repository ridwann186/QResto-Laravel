
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QResto - Menu</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            color: #222;
        }

        header {
            background: #202020;
            color: white;
            padding: 22px 6%;
        }

        header h1 {
            margin: 0;
            color: #f5a623;
        }

        header p { color: #ddd; }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 18px;
        }

        .table-info, .cart, .menu-card {
            background: white;
            border-radius: 13px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .table-info {
            border-left: 5px solid #f5a623;
            margin-bottom: 28px;
        }

        .menu-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .menu-card { padding: 18px; }

        .menu-icon {
    height: 160px;
    border-radius: 10px;
    background: #fff1d6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 55px;
    overflow: hidden;
    margin-bottom: 12px;
}

.menu-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

        .price {
            color: #d97706;
            font-weight: bold;
        }

        button {
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            cursor: pointer;
            font-weight: bold;
        }

        .add-button {
            background: #f5a623;
            width: 100%;
            margin-top: 10px;
        }

        .cart { margin-top: 30px; }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #eee;
        }

        .quantity button { background: #eee; }

        .total {
            display: flex;
            justify-content: space-between;
            font-size: 19px;
            font-weight: bold;
            margin-top: 20px;
        }

        .checkout {
            width: 100%;
            background: #202020;
            color: white;
            margin-top: 20px;
            padding: 14px;
        }

        .empty { color: #888; }
        .search-box {
    margin: 20px 0;
}

.search-box input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 15px;
    outline: none;
}

.search-box input:focus {
    border-color: #f5a623;
}

.filter-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.filter-buttons button {
    background: #eee;
    color: #333;
}

.filter-buttons button.active {
    background: #f5a623;
    color: #222;
}

.menu-section.hidden,
.menu-card.hidden {
    display: none;
}
    </style>
</head>

<body>

<header>
    <h1>QResto</h1>
    <p>Pesan makanan favoritmu dengan mudah.</p>
</header>

<div class="container">

    <div class="table-info">
        <h3>Informasi Pemesanan</h3>
        <p>Tipe: <strong>Dine In</strong></p>
        <p>Nomor Meja: <strong>{{ $tableCode }}</strong></p>
    </div>

    <div class="search-box">
    <input
        type="text"
        id="search-menu"
        placeholder="Cari makanan atau minuman..."
        oninput="filterMenus()"
    >
</div>

<div class="filter-buttons">
    <button class="active" onclick="setCategory('semua', this)">
        Semua
    </button>

    <button onclick="setCategory('makanan', this)">
        Makanan
    </button>

    <button onclick="setCategory('minuman', this)">
        Minuman
    </button>
</div>
    
<section class="menu-section" data-section="makanan">
    <h2>Menu Makanan</h2>

    <div class="menu-list">
        @forelse ($menus->where('category', 'makanan') as $menu)
            <div class="menu-card"
                data-category="makanan"
                data-name="{{ strtolower($menu->name) }}">

                <div class="menu-icon">
    @if ($menu->image)
        <img src="{{ asset('storage/' . $menu->image) }}"
             alt="{{ $menu->name }}">
    @else
        🍽️
    @endif
</div>

                <h3>{{ $menu->name }}</h3>
                <p>{{ $menu->description }}</p>

                <p class="price">
                    Rp{{ number_format($menu->price, 0, ',', '.') }}
                </p>

                <button class="add-button"
                    onclick="addToCart({{ $menu->id }})">
                    + Tambah
                </button>
            </div>
        @empty
            <p>Belum ada menu makanan tersedia.</p>
        @endforelse
    </div>
</section>

<section class="menu-section" data-section="minuman">
    <h2>Menu Minuman</h2>

    <div class="menu-list">
        @forelse ($menus->where('category', 'minuman') as $menu)
            <div class="menu-card"
                data-category="minuman"
                data-name="{{ strtolower($menu->name) }}">
                 
                <div class="menu-icon">
    @if ($menu->image)
        <img src="{{ asset('storage/' . $menu->image) }}"
             alt="{{ $menu->name }}">
    @else
        🥤
    @endif
</div>

                <h3>{{ $menu->name }}</h3>
                <p>{{ $menu->description }}</p>

                <p class="price">
                    Rp{{ number_format($menu->price, 0, ',', '.') }}
                </p>

                <button class="add-button"
                    onclick="addToCart({{ $menu->id }})">
                    + Tambah
                </button>
            </div>
        @empty
            <p>Belum ada menu minuman tersedia.</p>
        @endforelse
    </div>
</section>


        <div class="cart">

    <h2>🛒 Keranjang Saya</h2>

    <div id="cart-items">
        <p class="empty">Belum ada menu yang dipilih.</p>
    </div>

    <div class="total">
        <span>Total</span>
        <span id="total">Rp0</span>
    </div>

    <button class="checkout" onclick="checkout()">
        Lanjut Checkout
    </button>

</div>


<script>
    const menus = {{ Illuminate\Support\Js::from(
    $menus->map(function ($menu) {
        return [
            'id' => $menu->id,
            'name' => $menu->name,
            'price' => (float) $menu->price,
        ];
    })->values()
) }};

    let cart = {};

// FITUR FILTER DAN PENCARIAN MENU
let selectedCategory = 'semua';

function setCategory(category, button) {
    selectedCategory = category;

    document.querySelectorAll('.filter-buttons button')
        .forEach(btn => btn.classList.remove('active'));

    button.classList.add('active');

    filterMenus();
}

function filterMenus() {
    const keyword = document
        .getElementById('search-menu')
        .value.toLowerCase();

    document.querySelectorAll('.menu-card').forEach(card => {
        const name = card.dataset.name;
        const category = card.dataset.category;

        const matchName = name.includes(keyword);

        const matchCategory =
            selectedCategory === 'semua' ||
            selectedCategory === category;

        card.classList.toggle(
            'hidden',
            !(matchName && matchCategory)
        );
    });

    document.querySelectorAll('.menu-section').forEach(section => {
        const visibleCards = [
            ...section.querySelectorAll('.menu-card')
        ].some(card => !card.classList.contains('hidden'));

        section.classList.toggle('hidden', !visibleCards);
    });
}

// FITUR KERANJANG
function addToCart(id) {
    cart[id] = (cart[id] || 0) + 1;
    renderCart();
}


    function changeQuantity(id, amount) {
        cart[id] += amount;

        if (cart[id] <= 0) {
            delete cart[id];
        }

        renderCart();
    }

    function formatRupiah(number) {
        return 'Rp' + number.toLocaleString('id-ID');
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        container.innerHTML = '';

        let total = 0;

        for (const id in cart) {
            const menu = menus.find(item => item.id == id);

            if (!menu) continue;

            const quantity = cart[id];
            const subtotal = menu.price * quantity;

            total += subtotal;

            const item = document.createElement('div');
            item.className = 'cart-item';

            const info = document.createElement('div');
            const name = document.createElement('strong');
            name.textContent = menu.name;

            const price = document.createElement('div');
            price.textContent = formatRupiah(subtotal);

            info.append(name, price);

            const controls = document.createElement('div');

            const minus = document.createElement('button');
            minus.textContent = '-';
            minus.onclick = () => changeQuantity(id, -1);

            const count = document.createElement('span');
            count.textContent = ' ' + quantity + ' ';

            const plus = document.createElement('button');
            plus.textContent = '+';
            plus.onclick = () => changeQuantity(id, 1);

            controls.append(minus, count, plus);
            item.append(info, controls);
            container.appendChild(item);
        }

        if (Object.keys(cart).length === 0) {
            container.innerHTML =
                '<p class="empty">Belum ada menu yang dipilih.</p>';
        }

        document.getElementById('total').textContent =
            formatRupiah(total);
    }

    async function checkout() {
    const items = Object.keys(cart).map(id => ({
        id: Number(id),
        quantity: cart[id]
    }));

    if (items.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    const checkoutButton = document.querySelector('.checkout');

    // Cegah checkout berkali-kali
    checkoutButton.disabled = true;
    checkoutButton.textContent = 'Memproses Pesanan...';

    try {
        const response = await fetch(
            "{{ route('customer.checkout', $table->qr_token) }}",
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
                },
                body: JSON.stringify({ items: items })
            }
        );

        if (response.redirected) {
            window.location.href = response.url;
            return;
        }

        const result = await response.json();

        if (!response.ok) {
            const message = result.errors
                ? Object.values(result.errors).flat().join('\n')
                : 'Checkout gagal. Silakan coba lagi.';

            alert(message);
            return;
        }

        alert('Pesanan berhasil dibuat!');

    } catch (error) {
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');

    } finally {
        checkoutButton.disabled = false;
        checkoutButton.textContent = 'Lanjut Checkout';
    }
}

</script>

</body>
</html>
