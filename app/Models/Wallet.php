<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wallet extends Model
{
    // relacja 1:1
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserWallets()
    {
        return $this->where('user_id', Auth::user()->id)->get();
    }

    public function getWalletInfo($walletName)
    {
        return $this->where('name', $walletName)->first();
    }
}
