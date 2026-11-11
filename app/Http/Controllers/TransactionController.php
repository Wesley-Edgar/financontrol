<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Mostra somente as movimentações do usuário autenticado.
    public function index()
    {
        $userId = auth()->id();
        $transactions = Transaction::where('user_id', $userId)->latest()->get();

        $incomes = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $expenses = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $balance = $incomes - $expenses;

        $chartData = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('index', compact('transactions', 'balance', 'incomes', 'expenses', 'chartData'));
    }

    // Valida e salva uma movimentação diretamente na conta atual.
    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
        ]);

        $request->user()->transactions()->create($data);

        return redirect()->route('transactions.index')->with('success', 'Movimentação adicionada!');
    }

    // A consulta já exige o ID do usuário: ninguém exclui dados de outra conta.
    public function destroy(int $transaction)
    {
        $item = auth()->user()->transactions()->findOrFail($transaction);
        $item->delete();

        return redirect()->route('transactions.index')->with('success', 'Movimentação removida!');
    }
}
