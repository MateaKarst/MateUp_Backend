<div>
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('error'))
            <div class="alert alert-danger" id="error-alert">
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>

    <form>
        <div class="form-group d-flex justify-content-center align-items-center">
            <button type="button" class="btn btn-primary text-center" style="width: 100px;" wire:click.prevent="logout"><strong>Logout</strong></button> 
        </div>
    </form>
</div>