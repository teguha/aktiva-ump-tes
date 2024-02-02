<div class="alert alert-custom alert-light-danger fade show py-4" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">
        @foreach ($alerts as $text)
            <p>{!! $text !!}</p>
        @endforeach
    </div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>