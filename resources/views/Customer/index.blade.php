<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QResto - Pelanggan</title>
</head>
<body>

    <h1>QResto - Website Pelanggan</h1>

    <h2>Jenis Pesanan: {{ $jenisPesanan }}</h2>

    @if($idMeja)
        <h3>Nomor Meja: {{ $idMeja }}</h3>
    @else
        <h3>Tanpa Nomor Meja</h3>
    @endif

</body>
</html>