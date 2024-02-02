@extends('layouts.pageSubmit')
@section('action', route($routes.'.update', $record->id))

@section('card-body')

    @section('page-content')

    @method('PUT')


    <!-- layouts form -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                @section('card-header')
                    <div class="card-header">
                        <h3 class="card-title">@yield('card-title', $title)</h3>
                        <div class="card-toolbar">
                            @section('card-toolbar')
                                @include('layouts.forms.btnBackTop')
                            @show
                        </div>
                    </div>
                @show

                <div class="card-body">
                    @csrf
                    @include('termin.subs.card-1')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('termin.subs.card-2')
                </div>
            </div>
        </div>
    </div>
     <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('termin.subs.card-3')
                </div>
            </div>
        </div>
    </div>
    <!-- end of layouts form -->

    <!-- card bottom -->
    <div class="row">
        <div class="col-sm-12 col-md-6">

        </div>
        <!-- right -->
        <div class="col-sm-12 col-md-6">
            <div class="card card-custom">

                <div class="card-header">
                    <h3 class="card-title">@yield('card-title', 'Aksi')</h3>
                </div>
                <div class="card-body">
                    <div class="pull-right">
                        @section('buttons')
                         <button type="button"
                            class="btn btn-info align-items-center base-form--postByUrl"
                            data-swal-title="Konfirmasi Menerima Pembayaran Termin {{ $record->aktiva->code }} ?"
                            data-url="{{ route($routes.'.confirm', $record) }}">
                            {{ __('Konfirmasi Terbayar') }}
                        </button>
                        @show
                    </div>
                </div>

            </div>
        </div>
        <!-- end of right -->

    </div>
    <!-- end of card bottom -->

    @show

@endsection
@push('scripts')
<script>
</script>
@endpush

