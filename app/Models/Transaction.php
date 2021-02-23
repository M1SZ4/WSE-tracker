<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['wallet_id', 'stock_id', 'type', 'amount', 'price', 'comission', 'sum', 'data', 'comment'];

    public function wallet() {
        return $this->hasMany(Wallet::class);
    }
}
