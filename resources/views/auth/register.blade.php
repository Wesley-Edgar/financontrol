<!DOCTYPE html>
<html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Criar conta | FinanControl</title></head><body><h1>Criar conta</h1>
@if($errors->any())<p>{{ $errors->first() }}</p>@endif
<form method="POST" action="{{ route('register.submit') }}">@csrf
<label>CPF</label><input name="cpf" required>
<label>Data de nascimento</label><input type="date" name="birth_date" required>
<label>Senha de 6 dígitos</label><input type="password" name="password" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required>
<label>Confirme a senha</label><input type="password" name="password_confirmation" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required>
<button type="submit">Criar conta</button></form><a href="{{ route('login') }}">Já tenho conta</a></body></html>
