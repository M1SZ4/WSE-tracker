<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;

class WalletsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::user()->id)->get();
        return view('wallets', ['wallets'=>$wallets]);
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
    public function store(WalletRequest $request)
    {
        $wallet = new Wallet();
        $wallet->user_id = Auth::user()->id; // id of the currently logged in user
        $wallet->name = $request->name; // name of new wallet
        if ($request->public) { // default value is 0, so wallet is private
            $wallet->public = "1";
        }
        else {
            $wallet->public = "0";
        }

        $wallet->save();
        return redirect()->route('wallets.index')->with('status', 'Pomyślnie utworzono portfel');
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
    public function update(WalletRequest $request, Wallet $wallet)
    {
        $wallet->name = $request->name;
        $wallet->save();

        return redirect()->route('wallets.index')->with('status', 'Pomyślnie zmieniono nazwę');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()->route('wallets.index')->with('status', 'Pomyślnie usunięto');
    }
}
