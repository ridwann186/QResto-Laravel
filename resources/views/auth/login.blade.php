
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staf - QResto</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #222;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-icon {
            width: 65px;
            height: 65px;
            background: #fff1d6;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 14px;
        }

        .logo h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .logo h1 span {
            color: #f5a623;
        }

        .logo p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #f5a623;
        }

        .error {
            color: #d93025;
            font-size: 13px;
            margin-top: 7px;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 9px;
            background: #f5a623;
            color: #222;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
        }

        .login-button:hover {
            background: #e99a15;
        }

        .footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        <div class="logo-icon">🍽️</div>

        <h1>Q<span>Resto</span></h1>

        <p>Silakan login untuk mengakses sistem staf.</p>
    </div>

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email"
                required
                autofocus
            >

            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >
        </div>

        <button type="submit" class="login-button">
            Login
        </button>

    </form>

    <div class="footer">
        &copy; {{ date('Y') }} QResto. All Rights Reserved.
    </div>

</div>

</body>
</html>
