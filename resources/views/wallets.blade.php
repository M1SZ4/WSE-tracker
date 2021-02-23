@extends('layouts.app')

@section('content')
    @include('modals.addWallet')

    <div class="container-fluid">
        @auth
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Portfele</h1>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Add wallet button --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addWalletModal">
                    Dodaj portfel</button>
            </div>
        </div>

        @foreach($wallets as $wallet)
        <!-- Dropdown card with wallets names -->
            @include('modals.updateWallet')
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('wallet.show', $wallet->name) }}">
                            {{ $wallet->name }}</a></h6>
                    <div class="dropdown no-arrow">

                        <button class="btn btn-warning btn-icon-split" type="button" data-toggle="modal" data-target="#updateWalletModal{{ $wallet->id }}">
                            <span class="icon text-white-50">
                                        <i class="fas fa-exclamation-triangle"></i>
                            </span>
                            <span class="text">Edytuj</span>
                        </button>

                        <form method="POST" action="{{ route('wallets.destroy',  $wallet->id) }}" style="display: inline-block">
                            @csrf
                            @method('DELETE')
                            <div class="my-2"></div>
                            <button type="submit" onclick="return confirm('Jesteś pewien?')" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                    </span>
                                <span class="text">Usuń</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        </div>

        @endauth
        @guest
            <div class="table-container">
                <b>Zaloguj się aby stworzyć portfel.</b>
            </div>
        @endguest

@endsection

