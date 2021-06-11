<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function wallet()
    {
//        relacja 1-n
        return $this->hasMany(Wallet::class);
    }
    use HasFactory;

    public function getFullTable()
    {
        return $this->get();
    }

    public function getSharesFromWalletAsDataPoints($walletStocks)
    {
        $dataPoints = []; // array for chart in user wallet view
        foreach ($walletStocks as $ws) {
            $stockInfo = $this->select('name', 'price')->where('id',$ws->stock_id)->first();
            array_push($dataPoints, array("label"=>($stockInfo->name), "y"=>($stockInfo->price * $ws->amount)));
        }

        return $dataPoints;
    }
}
