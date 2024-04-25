<div>
    <div class="row mb-4">
        <div class="col-md-12">
            @if(session()->has('message'))
            <div class="alert alert-success" id="message-alert">
                {{ session('message') }}
            </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger" id="error-alert">
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>

    <form>
        <div class="form-group mb-3">
            <div class="row">
                <div class="col">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" wire:model="email">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="row">
                <div class="col">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" wire:model="password">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="form-group d-flex justify-content-center align-items-center">
            <button type="button" class="btn btn-primary text-center" style="width: 150px;" wire:click.prevent="login"><strong>Login</strong></button>
        </div>
    </form>
</div>