<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletStocks extends Model
{
    use HasFactory;

    protected $fillable = ['wallet_id', 'stock_id', 'amount', 'price', 'comission', 'sum', 'data'];

    public function wallet() {
        return $this->hasMany(Wallet::class);
    }
}
