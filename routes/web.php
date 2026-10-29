<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

// Rota principal: Quando acessar http://127.0.0 vai direto para o painel financeiro
Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');

// Rotas auxiliares para salvar e deletar as transações na web
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
