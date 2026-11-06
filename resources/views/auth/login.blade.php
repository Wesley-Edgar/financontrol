<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | FinanControl</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #0f172a, #172554); color: #1f2937; }
        .login-card { width: 100%; max-width: 430px; background: #fff; padding: 38px; border-radius: 18px; box-shadow: 0 24px 60px rgba(0,0,0,.25); }
        .brand { text-align: center; margin-bottom: 30px; }
        .brand-mark { display: inline-grid; place-items: center; width: 54px; height: 54px; border-radius: 15px; background: #2563eb; color: white; font-size: 25px; margin-bottom: 12px; }
        h1 { margin: 0; color: #111827; font-size: 25px; }
        .subtitle { margin: 8px 0 0; color: #6b7280; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 650; color: #374151; }
        input { width: 100%; padding: 13px 14px; border: 1px solid #d1d5db; border-radius: 9px; font-size: 15px; outline: none; transition: border .2s, box-shadow .2s; }
        input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
        .btn { width: 100%; border: 0; border-radius: 9px; padding: 14px; background: #2563eb; color: white; font-size: 15px; font-weight: 700; cursor: pointer; margin-top: 5px; }
        .btn:hover { background: #1d4ed8; }
        .helper { margin-top: 22px; text-align: center; color: #6b7280; font-size: 12px; }
        .error { color: #b91c1c; font-size: 12px; margin-top: 6px; }
        @media (max-width: 480px) { .login-card { padding: 28px 22px; } }
    </style>
</head>
<body>
    <main class="login-card">
        <header class="brand">
            <div class="brand-mark">₣</div>
            <h1>FinanControl</h1>
            <p class="subtitle">Acesse sua área de controle financeiro</p>
        </header>

        <!-- Formulário visual de acesso. A autenticação será conectada ao backend na próxima etapa. -->
        <form>
            @csrf
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input id="cpf" name="cpf" type="text" placeholder="000.000.000-00" maxlength="14" autocomplete="username" required>
            </div>

            <div class="form-group">
                <label for="birth_date">Data de nascimento</label>
                <input id="birth_date" name="birth_date" type="date" autocomplete="bday" required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input id="password" name="password" type="password" placeholder="Digite sua senha" autocomplete="current-password" required>
            </div>

            <button class="btn" type="submit">Entrar</button>
        </form>

        <p class="helper">Seus dados financeiros merecem segurança.</p>
    </main>

    <script>
        // Aplica a máscara visual do CPF enquanto o usuário digita.
        document.getElementById('cpf').addEventListener('input', function (event) {
            let cpf = event.target.value.replace(/\D/g, '').slice(0, 11);
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            event.target.value = cpf;
        });
    </script>
</body>
</html>