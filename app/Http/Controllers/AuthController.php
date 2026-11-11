<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Exibe a tela de entrada.
    public function showLogin()
    {
        return view('auth.login');
    }

    // Exibe a tela de criação de conta.
    public function showRegister()
    {
        return view('auth.register');
    }

    // Valida CPF, data de nascimento e senha para entrar.
    public function login(Request $request)
    {
        $data = $request->validate([
            'cpf' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'password' => ['required', 'digits:6'],
        ]);

        $data['cpf'] = $this->normalizeCpf($data['cpf']);

        if (!Auth::attempt($data, false)) {
            throw ValidationException::withMessages([
                'cpf' => 'CPF, data de nascimento ou senha inválidos.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('transactions.index'));
    }

    // Cria a conta e vincula a ela as movimentações antigas sem proprietário.
    public function register(Request $request)
    {
        $data = $request->validate([
            'cpf' => ['required', 'string'],
            'birth_date' => ['required', 'date', 'before:today'],
            'password' => ['required', 'digits:6', 'confirmed'],
        ]);

        $cpf = $this->normalizeCpf($data['cpf']);

        if (!$this->isValidCpf($cpf)) {
            throw ValidationException::withMessages([
                'cpf' => 'Informe um CPF válido.',
            ]);
        }

        if (User::where('cpf', $cpf)->exists()) {
            throw ValidationException::withMessages([
                'cpf' => 'Este CPF já possui uma conta. Entre nela.',
            ]);
        }

        DB::transaction(function () use ($cpf, $data) {
            // O cast "hashed" do modelo protege a senha antes de gravá-la.
            $user = User::create([
                'cpf' => $cpf,
                'birth_date' => $data['birth_date'],
                'password' => $data['password'],
            ]);

            // As movimentações antigas eram deste único proprietário.
            // Só associa registros ainda sem user_id; não altera registros já vinculados.
            DB::table('transactions')
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        });

        Auth::attempt([
            'cpf' => $cpf,
            'birth_date' => $data['birth_date'],
            'password' => $data['password'],
        ]);

        $request->session()->regenerate();

        return redirect()->route('transactions.index')
            ->with('success', 'Conta criada e movimentações anteriores vinculadas com sucesso!');
    }

    // Encerra a sessão com segurança.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Você saiu da sua conta.');
    }

    // Remove pontos, traços e espaços digitados no CPF.
    private function normalizeCpf(string $cpf): string
    {
        return preg_replace('/\D/', '', $cpf);
    }

    // Valida os dígitos verificadores do CPF.
    private function isValidCpf(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($position = 9; $position <= 10; $position++) {
            $sum = 0;

            for ($i = 0; $i < $position; $i++) {
                $sum += (int) $cpf[$i] * (($position + 1) - $i);
            }

            $digit = ($sum * 10) % 11;
            $digit = $digit === 10 ? 0 : $digit;

            if ($digit !== (int) $cpf[$position]) {
                return false;
            }
        }

        return true;
    }
}
