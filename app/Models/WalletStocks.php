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


//$wallet = DB::table('wallets')->where('name', $name)->first();
//$transactions = DB::table('transactions')->where('wallet_id', $wallet->id)->get();
//$walletStocks = DB::table('wallet_stocks')->where('wallet_id', $wallet->id)->get();
//
//$dataPoints = array(); // array for chart in user wallet view
//foreach ($walletStocks as $ws) {
//$stockInfo = Stock::select('name', 'price')->where('id',$ws->stock_id)->first();
//array_push($dataPoints, array("label"=>($stockInfo->name), "y"=>($stockInfo->price * $ws->amount)));
//}

    public function getSharesForWallet($walletId)
    {
        return $this->where('wallet_id', $walletId)->get();
    }
}
