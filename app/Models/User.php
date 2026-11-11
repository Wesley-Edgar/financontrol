<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Campos permitidos para criação/atualização da conta.
    protected $fillable = ['cpf', 'birth_date', 'password'];

    // Nunca exponha senha ou token em serializações.
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date:Y-m-d',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Um usuário possui várias receitas e despesas.
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
