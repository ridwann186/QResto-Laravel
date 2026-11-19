
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - QResto</title>

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
            max-width: 650px;
            margin: auto;
        }

        .form-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
        }

        h1 {
            margin-top: 0;
        }

        .description {
            color: #777;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            font-family: Arial, sans-serif;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #f5a623;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        .preview {
            display: none;
            width: 150px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 12px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button, .back-button {
            padding: 13px 18px;
            border: 0;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .save {
            background: #f5a623;
            color: #222;
            flex: 1;
        }

        .back-button {
            background: #eee;
            color: #333;
        }

        @media (max-width: 500px) {
            body {
                padding: 15px;
            }

            .form-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="form-card">

        <h1>Tambah Menu</h1>
        <p class="description">
            Masukkan informasi makanan atau minuman baru.
        </p>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:18px;">
                Ada data yang belum sesuai. Silakan periksa kembali formulir.
            </div>
        @endif

        <form
            action="{{ route('admin.menus.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label>Nama Menu</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Ayam Geprek"
                    required>

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Deskripsi Menu</label>
                <textarea
                    name="description"
                    placeholder="Contoh: Ayam goreng dengan sambal pedas">{{ old('description') }}</textarea>

                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input
                    type="number"
                    name="price"
                    value="{{ old('price') }}"
                    min="0"
                    step="0.01"
                    placeholder="Contoh: 18000"
                    required>

                @error('price')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="makanan" {{ old('category') == 'makanan' ? 'selected' : '' }}>
                        Makanan
                    </option>
                    <option value="minuman" {{ old('category') == 'minuman' ? 'selected' : '' }}>
                        Minuman
                    </option>
                </select>

                @error('category')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Foto Menu</label>
                <input
                    type="file"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    onchange="previewImage(event)">

                <small style="color:#777;">
                    Format JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                </small>

                <img id="image-preview" class="preview" alt="Pratinjau foto menu">

                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Status Menu</label>
                <select name="is_available" required>
                    <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>
                        Tersedia
                    </option>
                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>
                        Tidak Tersedia
                    </option>
                </select>

                @error('is_available')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="buttons">
                <a href="{{ route('admin.menus.index') }}" class="back-button">
                    Kembali
                </a>

                <button type="submit" class="save">
                    Simpan Menu
                </button>
            </div>

        </form>

    </div>

</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('image-preview');
        const file = event.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>

</body>
</html>
