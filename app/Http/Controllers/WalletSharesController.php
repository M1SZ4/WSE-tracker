<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletSharesRequest;
use App\Models\WalletStocks;
use App\Models\transaction;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\lessThanOrEqual;

class WalletSharesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletSharesRequest $request)
    {
        $stock_id = Stock::where('name', $request->name)->value('id');

        Transaction::create(array_merge($request->all(), [
            'stock_id' => $stock_id,
            'sum' => ($request->price * $request->amount) + $request->comission,
        ]));

//        if shares with the same id are in this wallet then update amount and prices
//        else create new row in table wallet_stocks
        $stockToWallet = WalletStocks::firstOrNew(['wallet_id' => $request->wallet_id, 'stock_id' => $stock_id]);

        $stockToWallet->amount += $request->amount;
        $stockToWallet->comission += $request->comission;
        if ( $stockToWallet->price === null) {
            $stockToWallet->price = $request->price;
        } else {
            $stockToWallet->price = ($stockToWallet->price + $request->price) / 2;
        }
        $stockToWallet->sum += ($request->price * $request->amount) + $request->comission;
        $stockToWallet->data = $request->data;

        $stockToWallet->save();

        return redirect()->route('wallet.show', $request->wallet_name);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $stocks = DB::table('stocks')->get();
        $wallet = DB::table('wallets')->where('name', $name)->first();
        $transactions = DB::table('transactions')->where('wallet_id', $wallet->id)->get();
        $walletStocks = DB::table('wallet_stocks')->where('wallet_id', $wallet->id)->get();

        $dataPoints = array(); // array for chart in user wallet view
        foreach ($walletStocks as $ws) {
            $stockInfo = Stock::select('name', 'price')->where('id',$ws->stock_id)->first();
            array_push($dataPoints, array("label"=>($stockInfo->name), "y"=>($stockInfo->price * $ws->amount)));
        }

        return view('userWallet')->with([
            'wallet'=>$wallet,
            'transactions'=>$transactions,
            'walletStocks'=>$walletStocks,
            'dataPoints'=>$dataPoints,
            'stocks'=>$stocks,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WalletSharesRequest $request, $name)
    {
        $stock_id = Stock::where('name', $request->name)->value('id');

        // find information about share in this wallet
        $stockFromWallet = WalletStocks::firstOrNew(['wallet_id' => $request->wallet_id, 'stock_id' => $stock_id]);

        $amountBeforeSell = $stockFromWallet->amount;
        $amountToSell = $request->amount;

        if($amountBeforeSell == $amountToSell) {
            $stockFromWallet->delete();
        } else if ($amountBeforeSell > $amountToSell) {
            $stockFromWallet->amount = $amountBeforeSell - $amountToSell;
            $stockFromWallet->sum -= ($request->price * $request->amount - $request->comission); //odjemuje prowizje od zainwestowanej kwoty
            $stockFromWallet->save();
        } else {
            return redirect()->route('wallet.show', $request->wallet_name)->with('status', 'Błędna ilość akcji!');
        }

        Transaction::create(array_merge($request->all(), [
            'stock_id' => $stock_id,
            'sum' => (($request->price) * ($request->amount)) + ($request->comission)
        ]));

        return redirect()->route('wallet.show', $request->wallet_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
