@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @auth
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Portfele</h1>
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

        {{-- Add wallet button --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addWalletModal">
                    Dodaj portfel</button>
            </div>
        </div>

        @foreach($wallets as $wallet)
        <!-- Dropdown card with wallets names -->
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                    <h6 class="m-0 font-weight-bold text-primary"><a href="{{ route('show', $wallet->name) }}">
                            {{ $wallet->name }}</a></h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
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
                    <form action="{{ route('store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user @error('name') is-invalid
                                    @enderror" id="name" placeholder="Nazwa" name="name" required>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" name="public" id="public">
                                    <label class="custom-control-label" for="customCheck">{{ __('Publiczny') }}</label>
                                </div>
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


@endsection

