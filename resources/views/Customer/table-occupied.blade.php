
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meja Sedang Digunakan - QResto</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            color: #222;
        }

        .container {
            width: 100%;
            max-width: 430px;
            padding: 35px 25px;
            background: white;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0,0,0,.06);
        }

        .icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff0e8;
            color: #e67e22;
            font-size: 38px;
        }

        h1 {
            margin: 0;
            color: #16834a;
        }

        h2 {
            margin-top: 25px;
            font-size: 21px;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .table-name {
            display: inline-block;
            margin: 10px 0;
            padding: 12px 22px;
            background: #f2f3f5;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .notice {
            margin-top: 20px;
            padding: 14px;
            background: #fff8e9;
            color: #8a641d;
            border-radius: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="icon">!</div>

        <h1>QResto</h1>

        <h2>Meja Sedang Digunakan</h2>

        <p>
            Mohon maaf, meja yang kamu pilih saat ini
            sedang digunakan pelanggan lain.
        </p>

        <div class="table-name">
            {{ $table->name }}
        </div>

        <div class="notice">
            Silakan pilih meja lain yang tersedia
            atau hubungi petugas restoran.
        </div>

    </div>
</body>
</html>
