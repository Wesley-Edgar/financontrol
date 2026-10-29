<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanControl Pro Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; border-b: 2px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 28px; color: #111827; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; rounded: 12px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
        .card span { font-size: 12px; font-weight: bold; text-transform: uppercase; color: #6b7280; }
        .card p { margin: 10px 0 0 0; font-size: 24px; font-weight: bold; }
        .main-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        @media (max-width: 768px) { .main-grid { grid-template-columns: 1fr; } }
        .panel { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; height: fit-content; }
        .panel h3 { margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #111827; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: bold; text-transform: uppercase; color: #4b5563; margin-bottom: 5px; }
        .form-control { w-full: 100%; width: -webkit-fill-available; border: 1px solid #d1d5db; padding: 10px; border-radius: 6px; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #2563eb; }
        .btn { width: 100%; background: #2563eb; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #1d4ed8; }
        .item-list { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; border-radius: 6px; margin-bottom: 10px; border: 1px solid #e5e7eb; }
        .item-income { background: #f0fdf4; border-left: 4px solid #16a34a; }
        .item-expense { background: #fef2f2; border-left: 4px solid #dc2626; }
        .badge { font-size: 11px; background: #e5e7eb; padding: 2px 8px; border-radius: 4px; color: #4b5563; font-weight: 500; }
        .delete-btn { background: none; border: none; color: #ef4444; font-weight: bold; cursor: pointer; }
        .delete-btn:hover { color: #b91c1c; }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <h1>📊 FinanControl Pro</h1>
            <p style="color: #6b7280; font-size: 14px;">Hoje: {{ date('d/m/Y') }}</p>
        </div>

        <div class="cards-grid">
            <div class="card" style="border-top: 4px solid #16a34a;">
                <span>Receitas (+)</span>
                <p style="color: #16a34a;">R$ {{ number_format($incomes, 2, ',', '.') }}</p>
            </div>
            <div class="card" style="border-top: 4px solid #dc2626;">
                <span>Despesas (-)</span>
                <p style="color: #dc2626;">R$ {{ number_format($expenses, 2, ',', '.') }}</p>
            </div>
            <div class="card" style="border-top: 4px solid #2563eb;">
                <span>Saldo Atual</span>
                <p style="color: #111827;">R$ {{ number_format($balance, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="main-grid">
            
            <div class="panel">
                <h3>Nova Movimentação</h3>
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Descrição</label>
                        <input type="text" name="description" placeholder="Ex: Mercado, Salário" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Valor (R$)</label>
                        <input type="number" step="0.01" name="amount" placeholder="0,00" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="type" class="form-control">
                            <option value="income">Receita</option>
                            <option value="expense">Despesa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="category" class="form-control">
                            <option value="Alimentação">Alimentação</option>
                            <option value="Moradia">Moradia</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Lazer">Lazer</option>
                            <option value="Salário">Salário</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Confirmar Lançamento</button>
                </form>
            </div>

            <div class="panel">
                <h3>Fluxo de Caixa / Histórico</h3>
                
                @if($transactions->isEmpty())
                    <p style="color: #9ca3af; text-align: center; padding: 40px 0;">Nenhuma transação encontrada.<br><span style="font-size: 13px;">Cadastre seu primeiro item ao lado!</span></p>
                @else
                    <div style="max-height: 450px; overflow-y: auto;">
                        @foreach($transactions as $t)
                            <div class="item-list {{ $t->type == 'income' ? 'item-income' : 'item-expense' }}">
                                <div>
                                    <strong style="display: block; font-size: 15px;">{{ $t->description }}</strong>
                                    <span class="badge">{{ $t->category }}</span>
                                    <span style="font-size: 11px; color: #9ca3af; margin-left: 5px;">{{ $t->created_at->format('d/m H:i') }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <span style="font-weight: bold; font-size: 16px; color: {{ $t->type == 'income' ? '#16a34a' : '#111827' }}">
                                        {{ $t->type == 'income' ? '+' : '-' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                                    </span>
                                    <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="delete-btn" title="Excluir">Excluir</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</body>
</html>
