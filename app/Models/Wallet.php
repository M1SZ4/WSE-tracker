<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    // relacja 1:1
    public function user() {
        return $this->belongsTo(User::class);
    }
}
