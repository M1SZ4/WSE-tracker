@extends('layouts.app')

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Symbol</th>
                <th>Nazwa</th>
                <th>Kurs</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Symbol</th>
                <th>Nazwa</th>
                <th>Kurs</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <th>{{ $stock->symbol }}</th>
                    <th>{{ $stock->name }}</th>
                    <th>{{ $stock->price }}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
