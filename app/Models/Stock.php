<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function wallet() {
//        relacja 1-n
        return $this->hasMany(Wallet::class);
    }
    use HasFactory;
}
