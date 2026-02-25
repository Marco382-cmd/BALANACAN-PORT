<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Balanacan Port</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f2d4a 0%, #1a4a7a 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .login-card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            width: 100%; max-width: 400px;
            padding: 2.5rem 2rem;
        }
        .login-brand {
            text-align: center; margin-bottom: 2rem;
        }
        .login-icon { font-size: 2.5rem; }
        .login-title { font-size: 1.3rem; font-weight: 800; color: #0f2d4a; margin-top: .5rem; }
        .login-sub   { font-size: .82rem; color: #6c757d; margin-top: .2rem; }
        .form-group { margin-bottom: 1.1rem; }
        label { display: block; font-size: .82rem; font-weight: 700; color: #343a40; margin-bottom: .4rem; }
        input[type="email"],
        input[type="password"] {
            width: 100%; padding: .6rem .85rem;
            border: 1.5px solid #dee2e6; border-radius: 8px;
            font-size: .9rem; font-family: inherit;
            transition: border-color .15s;
        }
        input:focus { outline: none; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.15); }
        .input-error { border-color: #dc3545 !important; }
        .error-msg { font-size: .78rem; color: #dc3545; margin-top: .3rem; }
        .remember-row { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.5rem; }
        .remember-row input { width: auto; margin: 0; }
        .remember-row label { margin: 0; font-size: .82rem; color: #6c757d; }
        .btn-login {
            width: 100%; padding: .7rem 1rem;
            background: #0f2d4a; color: #fff;
            border: none; border-radius: 8px;
            font-size: .92rem; font-weight: 800; font-family: inherit;
            cursor: pointer; transition: background .15s;
        }
        .btn-login:hover { background: #1a4a7a; }
        .back-link { text-align: center; margin-top: 1.25rem; font-size: .8rem; }
        .back-link a { color: #0d6efd; text-decoration: none; font-weight: 600; }
        .alert-danger {
            background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;
            border-radius: 8px; padding: .75rem 1rem;
            font-size: .85rem; font-weight: 600;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <div class="login-icon">⚓</div>
            <div class="login-title">Balanacan Port</div>
            <div class="login-sub">Administrator Login</div>
        </div>

        @if($errors->any())
            <div class="alert-danger">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-danger">❌ {{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'input-error' : '' }}"
                       required autofocus autocomplete="email">
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="{{ $errors->has('password') ? 'input-error' : '' }}"
                       required autocomplete="current-password">
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Keep me signed in</label>
            </div>

            <button type="submit" class="btn-login">🔐 Sign In to Admin</button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}">← Back to public site</a>
        </div>
    </div>
</body>
</html>