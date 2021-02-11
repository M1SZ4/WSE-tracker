<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = DB::table('wallets')->where('user_id', Auth::user()->id)->get();
        return view('wallets', ['wallets'=>$wallets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $wallet = new Wallet();
        return view('wallet',['wallet' => $wallet]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
        ]);
        if (\Auth::user() == null) {
            return view('home'); // jezli uzytkownik nie jest zalogowany
        }
        $wallet = new Wallet();
        $wallet->user_id = \Auth::user()->id; //id zalogowanego uzytkownika
        $wallet->name = $request->name; //nazwa pola
        if ($request->public) {
            $wallet->public = "1";
        }
        else {
            $wallet->public = "0";
        }
        if ($wallet->save()) {
            return redirect('wallet');
        }
        return redirect('wallet');
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
        $wallet = DB::table('wallets')->where('name', $name)->get();
        $transactions = DB::table('transactions')->where('wallet_id', $wallet[0]->id)->get();
        $walletStocks = DB::table('wallet_stocks')->where('wallet_id', $wallet[0]->id)->get();
//        array for chart in user wallet view
        $dataPoints = array();
        foreach ($walletStocks as $ws) {
            $stockName = (DB::table('stocks')->where('id',$ws->stock_id)->get())[0]->name;
            $stockPrice = (DB::table('stocks')->where('name', $stockName)->get())[0]->price;
            array_push($dataPoints, array("label"=>($stockName), "y"=>($stockPrice * $ws->amount)));
        }
        return view('userWallet',  ['wallets'=>$wallet], ['transactions'=>$transactions])
            ->with(['walletStocks'=>$walletStocks])->with(['dataPoints'=>$dataPoints])->with(['stocks'=>$stocks]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
