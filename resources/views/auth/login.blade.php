<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar | FinanControl</title>
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f1f5f9;font-family:Segoe UI,Arial,sans-serif;color:#172033}
        .box{width:min(420px,92vw);background:#fff;padding:34px;border-radius:18px;box-shadow:0 15px 45px #0f172a18}
        h1{margin:0 0 8px;color:#14532d}.muted{color:#64748b;margin:0 0 25px}
        label{display:block;font-weight:600;font-size:13px;margin:16px 0 7px}
        input{width:100%;padding:13px;border:1px solid #cbd5e1;border-radius:9px;font-size:15px}
        button{width:100%;margin-top:22px;padding:14px;border:0;border-radius:9px;background:#15803d;color:#fff;font-weight:700;font-size:15px;cursor:pointer}
        a{color:#15803d;font-weight:600;text-decoration:none}.foot{text-align:center;margin-top:22px;font-size:14px}
        .alert{padding:11px;border-radius:8px;background:#fef2f2;color:#b91c1c;margin:12px 0;font-size:13px}
        .success{background:#f0fdf4;color:#166534}
    </style>
</head>
<body>
<div class="box">
    <h1>FinanControl</h1>
    <p class="muted">Entre na sua conta para acessar suas finanças.</p>
    @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <label for="cpf">CPF</label>
        <input id="cpf" name="cpf" value="{{ old('cpf') }}" inputmode="numeric" autocomplete="username" placeholder="000.000.000-00" required>
        <label for="birth_date">Data de nascimento</label>
        <input id="birth_date" type="date" name="birth_date" required>
        <label for="password">Senha de 6 dígitos</label>
        <input id="password" type="password" name="password" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="current-password" placeholder="••••••" required>
        <button type="submit">Entrar na conta</button>
    </form>
    <div class="foot">Ainda não tem conta? <a href="{{ route('register') }}">Criar uma conta</a></div>
</div>
</body>
</html>
