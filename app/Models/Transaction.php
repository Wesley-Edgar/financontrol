<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'description', 'amount', 'type', 'category'];

    // Cada movimentação pertence a uma conta.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
