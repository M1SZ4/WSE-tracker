<!-- update new wallet Modal-->
<div class="modal fade" id="updateWalletModal{{ $wallet->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Zmień nazwe portfela</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="POST" action="{{ route('wallets.update', $wallet->name) }}" style="display: inline-block">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user @error('name') is-invalid
                                    @enderror" id="name" placeholder="Nowa nazwa" name="name" required>

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
                    <button class="btn btn-primary" type="submit">Zmień</button>
                </div>
            </form>
        </div>
    </div>
</div>
