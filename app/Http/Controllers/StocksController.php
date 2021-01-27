<?php

namespace App\Http\Controllers;

use App\Models\WalletStocks;
use App\Models\transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
class StocksController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $transactions = DB::table('transactions')->where('user_id', Auth::user()->id)->get()->get();
//        return view('userWallet', ['transactions'=>$transactions]);
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
    public function store(Request $request)
    {
        $this->validate($request, [
//            kurs, prowizje >0
        ]);
        if (\Auth::user() == null) {
            return view('home'); // jezli uzytkownik nie jest zalogowany
        }

        $stock_id = (Stock::select('id')->where('name', $request->name)->get())[0]->id;
        $wallet_id = (Wallet::select('id')->where('name', $request->wallet_name)->get())[0]->id;

        $transaction = new Transaction();
        $transaction->stock_id = $stock_id;
        $transaction->wallet_id = $wallet_id;
        $transaction->type = $request->transaction_type;
        $transaction->data = $request->date; //data
        $transaction->amount = $request->amount;
        $transaction->comission = $request->comission;
        $transaction->price = (($request->price)*($request->amount)) + ($request->comission); //cena
        $transaction->comment = $request->comment;


        $oldStock = (WalletStocks::where('wallet_id', $wallet_id)->where('stock_id', $stock_id)->first());


        if ($oldStock === null) {
            $WalletStocks = new WalletStocks();
            $WalletStocks->stock_id = $stock_id;
            $WalletStocks->wallet_id = $wallet_id;
            $WalletStocks->amount = $request->amount;
            $WalletStocks->comission = $request->comission;
            $WalletStocks->price = (($request->price)*($request->amount)) + ($request->comission); //cena
            $WalletStocks->data = $request->date; //data

            if ($transaction->save() and $WalletStocks->save()) {
                return redirect(route('show', $request->wallet_name));
            }
            return redirect(route('show', $request->wallet_name));//, $request->wallet_name)




        }
        else {
            $oldStock = (WalletStocks::where('wallet_id', $wallet_id)->where('stock_id', $stock_id)->get())[0];
            $oldStock->amount = $oldStock->amount + $request->amount;
            $oldStock->comission = $oldStock->comission + $request->comission;
            $oldStock->price = $oldStock->price + (($request->price)*($request->amount)) + ($request->comission);
            if($oldStock->save() and $transaction->save()) {
                return redirect(route('show', $request->wallet_name));
            }
            return redirect(route('show', $request->wallet_name));
        }

//        $this->validate($request, [
////            kurs, prowizje >0
//        ]);
//        if (\Auth::user() == null) {
//            return view('home'); // jezli uzytkownik nie jest zalogowany
//        }
//        $stock_id = (Stock::select('id')->where('name', $request->name)->get())[0]->id;
//        $wallet_id = (Wallet::select('id')->where('name', $request->wallet_name)->get())[0]->id;
//
//        $transaction = new Transaction();
//        $transaction->stock_id = $stock_id;
//        $transaction->wallet_id = $wallet_id;
//        $transaction->type = $request->transaction_type;
//        $transaction->data = $request->date; //data
//        $transaction->amount = $request->amount;
//        $transaction->comission = $request->comission;
//        $transaction->price = (($request->price)*($request->amount)) + ($request->comission); //cena
//        $transaction->comment = $request->comment;
//
//        $WalletStocks = new WalletStocks();
//        $WalletStocks->stock_id = $stock_id;
//        $WalletStocks->wallet_id = $wallet_id;
//        $WalletStocks->amount = $request->amount;
//        $WalletStocks->comission = $request->comission;
//        $WalletStocks->price = (($request->price)*($request->amount)) + ($request->comission); //cena
//        $WalletStocks->data = $request->date; //data
//
//        if ($transaction->save() and $WalletStocks->save()) {
//            return redirect(route('show', $request->wallet_name));
//        }
//        return redirect(route('show', $request->wallet_name));//, $request->wallet_name)
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
//        $comment = Comment::find($id); //Sprawdzenie czy użytkownik jest autorem komentarza
//        if (\Auth::user()->id != $comment->user_id) {
//            return back()->with(['success' => false, 'message_type' => 'danger', 'message' => 'Nie posiadasz uprawnień do przeprowadzenia tej operacji.']);
//        }
//        return view('commentsEditForm', ['comment'=>$comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->validate($request, [
//            kurs, prowizje >0
        ]);
        if (\Auth::user() == null) {
            return view('home'); // jezli uzytkownik nie jest zalogowany
        }
        $stock_id = (Stock::select('id')->where('name', $request->name)->get())[0]->id;
        $wallet_id = (Wallet::select('id')->where('name', $request->wallet_name)->get())[0]->id;

        $transaction = new Transaction();
        $transaction->stock_id = $stock_id;
        $transaction->wallet_id = $wallet_id;
        $transaction->type = $request->transaction_type;
        $transaction->data = $request->date; //data
        $transaction->amount = $request->amount;
        $transaction->comission = $request->comission;
        $transaction->price = (($request->price)*($request->amount)) + ($request->comission); //cena
        $transaction->comment = $request->comment;

        $ws = (WalletStocks::where('wallet_id', $wallet_id)->where('stock_id', $stock_id)->get())[0];
        var_dump($ws);
        $amountBeforeSell = $ws->amount;
        $amountToSell = $request->amount;

        if($amountBeforeSell == $amountToSell) {
            if($ws->delete() and $transaction->save()) {
                return redirect(route('show', $request->wallet_name));
            }
        }
        else {
            $ws->amount = $amountBeforeSell - $amountToSell;
            if($ws->save() and $transaction->save()) {
                return redirect(route('show', $request->wallet_name));
            }
            return redirect(route('show', $request->wallet_name));
        }

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
