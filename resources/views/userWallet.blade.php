@extends('layouts.app')

@section('content')
    @include('layouts.chart')
    @auth

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Twój portfel: {{ $wallets[0]->name }}</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Add or remove transaction buttons --}}
        <div class="row">
            <div class="col-xl-1 col-md-6 mb-4">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addWalletModal">
                    Dodaj operacje kupna</button>
            </div>
            <div class="col-xl-1 col-md-6 mb-4">
                <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#sell">
                    Dodaj operacje sprzedaży</button>
            </div>
        </div>


        <div class="row">

            <!-- Zainwestowana kwota -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Zainwestowana kwota</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $total_invested = DB::table('wallet_stocks')
                                    ->where('wallet_id', $wallets[0]->id)->sum('price') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wartość portfela -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Wartość portfela
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $total = (DB::table('wallet_stocks')
                                    ->join('stocks', 'stocks.id', '=', 'wallet_stocks.stock_id')
                                    ->select(DB::raw('sum(wallet_stocks.amount * stocks.price) AS total_price'))
                                    ->where('wallet_stocks.wallet_id', $wallets[0]->id)
                                    ->first())->total_price }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zysk -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    Zysk
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @if ($total_invested != 0)
                                        @if ( round(($total- $total_invested) * 100 / $total_invested, 2) > 0)
                                            <a style="color: green">
                                                {{ round(($total- $total_invested) * 100 / $total_invested, 2) }}%
                                            </a>
                                        @else
                                            <a style="color: red">
                                                {{ round(($total- $total_invested) * 100 / $total_invested, 2) }}%
                                            </a>
                                        @endif
                                    @else
                                    {{ __('0') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <!-- Donut Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Struktura portfela:</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        {{-- chart --}}
                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                @if($walletStocks->isNotEmpty())
                    <!-- Actual stocks in wallet table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Akcje w portfelu:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Zainwestowana kwota</th>
                                        <th>Średni kurs zakupu</th>
                                        <th>Ilość</th>
                                        <th>Aktualny kurs</th>
                                        <th>Zysk</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($walletStocks as $ws)
                                        <tr>
                                            <th>{{ (DB::table('stocks')->select('name')->where('id',$ws->stock_id)
                                                ->get())[0]->name }}</th>
                                            <th>{{ $ws->price }}</th>
                                            <th>{{ round(($ws->price / $ws->amount), 2) }}</th>
                                            <th>{{ $ws->amount }}</th>
                                            <th>{{ $actual_price = (DB::table('stocks')->select('price')
                                                ->where('id',$ws->stock_id)->get())[0]->price }}</th>
                                            @if ($profit = $actual_price * ($ws->amount) - ($ws->price) > 0)
                                                <th style="color: green">
                                                    {{ $profit = $actual_price * ($ws->amount) - ($ws->price)}}
                                                    ({{ round((($profit * 100) / $ws->price), 2) }}%)
                                                </th>
                                            @else
                                                <th style="color: red">
                                                    {{ $profit = $actual_price * ($ws->amount) - ($ws->price)}}
                                                    ({{ round((($profit * 100) / $ws->price), 2) }}%)
                                                </th>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($transactions->isNotEmpty())
        <!-- Transaction list table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transakcje:</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Data</th>
                            <th>Nazwa</th>
                            <th>Typ</th>
                            <th>Ilość</th>
                            <th>Prowizja</th>
                            <th>Kwota</th>
                            <th>Komentarz</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Data</th>
                            <th>Nazwa</th>
                            <th>Typ</th>
                            <th>Ilość</th>
                            <th>Prowizja</th>
                            <th>Kwota</th>
                            <th>Komentarz</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <th>{{ $transaction->data }}</th>
                                <th>{{ (DB::table('stocks')->select('name')->where('id',$transaction->stock_id)
                                    ->get())[0]->name }}</th>
                                <th>{{ $transaction->type }}</th>
                                <th>{{ $transaction->amount }}</th>
                                <th>{{ $transaction->comission }}</th>
                                <th>{{ $transaction->price }}</th>
                                <th>{{ $transaction->comment }}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    @endauth


    @guest
        <div class="table-container">
            <b>Musisz być zalogowany aby przeglądać portfel.</b>
        </div>
    @endguest


    <!-- Add new wallet Modal-->
    <div class="modal fade" id="addWalletModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Utwórz nowy portfel</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('store-transaction') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('wallet_name') is-invalid
                                @enderror" id="wallet_name" value="{{ $wallets[0]->name }}" name="wallet_name" readonly>

                            @error('wallet_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('transaction_type')
                                is-invalid @enderror" id="transaction_type" value="Kupno" name="transaction_type"
                                   readonly>

                            @error('transaction_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <select class="form-control form-control-user" name="name">
                                @foreach($stocks as $stock)
                                    <option value="{{ $stock->name }}">{{ $stock->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="date" class="form-control form-control-user @error('date') is-invalid
                                @enderror" id="date" placeholder="date" name="date" required>

                            @error('date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('price') is-invalid
                                @enderror" id="price" placeholder="Kurs" name="price">

                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('amount')
                                is-invalid @enderror" id="a,punt" placeholder="Ilość" name="amount">

                            @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('comission')
                                is-invalid @enderror" id="comission" placeholder="Prowizja" name="comission">

                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <textarea class="form-control form-control-user @error('comment') is-invalid @enderror"
                                      id="comment" name="comment" rows="4" cols="50"  placeholder="Komentarz">

                            </textarea>

                            @error('comment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Anuluj</button>
                        <button class="btn btn-primary" type="submit">Dodaj</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add new wallet Modal-->
    <div class="modal fade" id="sell" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Utwórz nowy portfel</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('update') }}" method="POST">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    <div class="modal-body">
                        <input id="wallet_id" name="wallet_id" type="hidden" value="{{ $wallets[0]->id }}">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('wallet_name') is-invalid
                                @enderror" id="wallet_name" value="{{ $wallets[0]->name }}" name="wallet_name" readonly>

                            @error('wallet_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('transaction_type')
                                is-invalid @enderror" id="transaction_type" value="Sprzedaż" name="transaction_type"
                                readonly>

                            @error('transaction_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <select class="form-control form-control-user" name="name">
                                @foreach($walletStocks as $ws)
                                    <option value="{{ $name = (DB::table('stocks')->select('name')
                                        ->where('id',$ws->stock_id)->get())[0]->name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="date" class="form-control form-control-user @error('date') is-invalid
                                @enderror" id="date" placeholder="date" name="date">

                            @error('date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('price') is-invalid
                                @enderror" id="price" placeholder="Kurs" name="price">

                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('amount')
                                is-invalid @enderror" id="amount" placeholder="Ilość" name="amount">

                            @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-user @error('comission')
                                is-invalid @enderror" id="comission" placeholder="Prowizja" name="comission">

                            @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <textarea class="form-control form-control-user @error('comment') is-invalid @enderror"
                                      id="comment" name="comment" rows="4" cols="50"  placeholder="Komentarz">

                            </textarea>

                            @error('comment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Anuluj</button>
                        <button class="btn btn-primary" type="submit">Sprzedaj</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
