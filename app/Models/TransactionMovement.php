<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionMovement extends Model
{
    use HasFactory;


    protected $fillable = [
        'transaction_type',
        'transaction_id',
        'credit_id',
        'returns_id',
        'void_transaction_id',

    ];

    public function transactionJoin()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function creditJoin()
    {
        return $this->belongsTo(Credit::class, 'credit_id');
    }

    public function returnsJoin()
    {
        return $this->belongsTo(Returns::class, 'returns_id');
    }
    public function voidTransactionJoin()
    {
        return $this->belongsTo(VoidTransaction::class, 'void_transaction_id');
    }

    public function scopeSearch($query, $value)
    {
        $value = strtolower($value);
        $value = trim($value);


        return $query->whereHas('transactionJoin', function ($query) use ($value) {
            $query->whereRaw('LOWER(transaction_number) like ?', ["%{$value}%"]);
        })
            ->orWhereHas('creditJoin.transactionJoin', function ($query) use ($value) {
                $query->whereRaw('LOWER(transaction_number) like ?', ["%{$value}%"]);
            })
            ->orWhereHas('returnsJoin.transactionJoin', function ($query) use ($value) {
                $query->whereRaw('LOWER(transaction_number) like ?', ["%{$value}%"]);
            })
            ->orWhereHas('voidTransactionJoin.transactionJoin', function ($query) use ($value) {
                $query->whereRaw('LOWER(transaction_number) like ?', ["%{$value}%"]);
            });
    }
}
