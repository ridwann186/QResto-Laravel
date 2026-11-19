
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Meja - QResto</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #f4f5f7;
            color: #222;
        }

        .container {
            max-width: 550px;
            margin: 40px auto;
            background: white;
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,.06);
        }

        h1 { margin-top: 0; }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        button, .back {
            display: inline-block;
            padding: 12px 18px;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
            margin-top: 22px;
        }

        button {
            background: #16834a;
            color: white;
        }

        .back {
            background: #eee;
            color: #222;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Tambah Meja</h1>
    <p>Masukkan informasi meja restoran.</p>

    <form action="{{ route('admin.tables.store') }}" method="POST">
        @csrf

        <label>Kode Meja</label>
        <input
            type="text"
            name="code"
            value="{{ old('code') }}"
            placeholder="Contoh: M01"
            required
        >
        @error('code')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Nama Meja</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            placeholder="Contoh: Meja 1"
            required
        >
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <br>

        <button type="submit">Simpan Meja</button>

        <a href="{{ route('admin.tables') }}" class="back">
            Kembali
        </a>
    </form>

</div>

</body>
</html>
