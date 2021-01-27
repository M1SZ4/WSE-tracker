@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @auth

                    {{ __('Jesteś zalogowany!') }}
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Zaloguj</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Rejestracja</a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
