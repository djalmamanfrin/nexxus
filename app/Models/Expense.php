<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model para a tabela de despesas.
 *
 * Representa as despenas do sistema via anexo do comprovante de pagamento
 */
class Expense extends Model
{
    protected $table = 'expenses';
    protected $fillable = [
        'notes',
    ];
}
