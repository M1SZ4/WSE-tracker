<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wallet_id')->unsigned();
            $table->bigInteger('stock_id')->unsigned();
            $table->integer('amount')->nullable(false);
            $table->float('price')->nullable(false);
            $table->float('comission');
            $table->date('data');
            $table->foreign('wallet_id')->references('id')->on('wallets')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('stock_id')->references('id')->on('stocks')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_stocks');
    }
}
