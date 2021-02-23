<!-- Add new stock to wallet-->
<div class="modal fade" id="addWalletModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dodaj transakcje kupna</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('wallet.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input id="wallet_id" name="wallet_id" type="hidden" value="{{ $wallet->id }}">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user @error('wallet_name') is-invalid
                                @enderror" id="wallet_name" value="{{ $wallet->name }}" name="wallet_name" readonly>

                        @error('wallet_name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control form-control-user @error('type')
                            is-invalid @enderror" id="type" value="Kupno" name="type"
                               readonly>

                        @error('type')
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
                        <input type="date" class="form-control form-control-user @error('data') is-invalid
                                @enderror" id="data" placeholder="data" name="data" required>

                        @error('data')
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
                    <button class="btn btn-primary" type="submit">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>
