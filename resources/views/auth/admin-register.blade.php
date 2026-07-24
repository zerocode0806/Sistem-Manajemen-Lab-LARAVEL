<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar Admin – LabSystem</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --bg: #F7F7F5; --surface: #FFFFFF; --border: #E8E8E3;
    --text: #18181B; --muted: #8C8C8A; --accent: #1A1A1A;
    --red: #DC2626; --red-soft: #FEF2F2; --radius: 10px;
}
body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); font-size: 14px; line-height: 1.5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
.login-wrap { width: 100%; max-width: 400px; }
.brand { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; justify-content: center; }
.brand-icon { width: 34px; height: 34px; background: var(--accent); border-radius: 9px; display: grid; place-items: center; }
.brand-icon i { color: #fff; font-size: 16px; }
.brand-text strong { font-size: 14px; font-weight: 600; }
.brand-text span { font-size: 11px; color: var(--muted); display: block; }
.login-header { text-align: center; margin-bottom: 24px; }
.login-header h1 { font-size: 22px; font-weight: 600; letter-spacing: -.3px; margin-bottom: 5px; }
.login-header p { font-size: 13.5px; color: var(--muted); }
.card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; }
.field-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.field-group label { font-size: 12.5px; font-weight: 600; color: var(--text); }
.field-group input { height: 40px; padding: 0 12px; border: 1px solid var(--border); border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13.5px; color: var(--text); background: var(--surface); outline: none; transition: border-color .15s; }
.field-group input:focus { border-color: var(--accent); }
.field-error { font-size: 12px; color: var(--red); margin-top: 2px; }
.alert-error { background: var(--red-soft); color: var(--red); border: 1px solid #fecaca; border-radius: 8px; padding: 12px 14px; font-size: 13px; margin-bottom: 18px; }
.btn-submit { width: 100%; height: 42px; background: var(--accent); color: #fff; border: none; border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity .15s; margin-top: 4px; }
.btn-submit:hover { opacity: .85; }
.login-footer { text-align: center; margin-top: 20px; font-size: 13px; color: var(--muted); }
.login-footer a { color: var(--accent); text-decoration: none; font-weight: 500; }
.login-footer a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="login-wrap">
    <div class="brand">
        <div class="brand-icon"><i class="bi bi-boxes"></i></div>
        <div class="brand-text">
            <strong>LabSystem</strong>
            <span>SISTEM MANAJEMEN LAB</span>
        </div>
    </div>
    <div class="login-header">
        <h1>Daftar Akun Admin</h1>
        <p>Buat akun admin baru untuk mengakses sistem</p>
    </div>
    <div class="card">
        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form action="{{ route('admin.register.submit') }}" method="POST">
            @csrf
            <div class="field-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                @error('nama')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@email.com">
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Username untuk login">
                @error('username')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
            </div>
            <button type="submit" class="btn-submit">Daftar</button>
        </form>
    </div>
    <div class="login-footer">
        Sudah punya akun? <a href="{{ route('admin.login') }}">Login di sini</a>
    </div>
</div>
</body>
</html>
