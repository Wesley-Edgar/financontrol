<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Listar transações e gerar o gráfico
    public function index()
    {
        $transactions = Transaction::latest()->get();
        
        $incomes = Transaction::where('type', 'income')->sum('amount');
        $expenses = Transaction::where('type', 'expense')->sum('amount');
        $balance = $incomes - $expenses;

        $chartData = Transaction::where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('index', compact('transactions', 'balance', 'incomes', 'expenses', 'chartData'));
    }

    // Salvar nova transação
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
        ]);

        Transaction::create($request->all());

        return redirect()->route('transactions.index')->with('success', 'Adicionado!');
    }

    // Excluir transação
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Removido!');
    }
}
