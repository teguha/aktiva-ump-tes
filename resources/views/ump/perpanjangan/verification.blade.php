@extends('layouts.pageSubmit')
@section('action', route($routes.'.update', $record))

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
                    @include('ump.perpanjangan.subs.card-1')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.perpanjangan.subs.card-2')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.perpanjangan.subs.card-3')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.perpanjangan.subs.card-4')
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
                    @section('buttons')
                        <div class="pull-right">
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-info dropdown-toggle {{ !empty($pill) ? 'btn-pill' : '' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mr-1 fa fa-save"></i> {{ __('Verifikasi') }}</button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button type="button" 
                                        class="dropdown-item align-items-center base-form--postByUrl" 
                                        data-swal-title="Setujui Perpanjangan UMP {{ $record->id_perpanjangan }} ?"
                                        data-url="{{ route($routes.'.verify', $record) }}">
                                        <i class="mr-3 fa fa-check text-primary"></i> {{ __('Setujui') }}
                                    </button>
                                    <button type="button" 
                                        class="dropdown-item align-items-center base-form--postByUrl" 
                                        data-swal-title="Tolak Perpanjangan UMP {{ $record->id_perpanjangan }} ?"
                                        data-url="{{ route($routes.'.reject', $record) }}">
                                        <i class="mr-3 fa fa-times text-danger"></i> {{ __('Tolak') }}
                                    </button>
                                    <button type="button" 
                                        class="dropdown-item align-items-center base-form--postByUrl" 
                                        data-swal-title="Ajukan revisi Perpanjangan UMP {{ $record->id_perpanjangan }} ?"
                                        data-url="{{ route($routes.'.revise', $record) }}">
                                        <i class="mr-3 fa fa-edit text-warning"></i> {{ __('Revisi') }}
                                    </button>
                                    <button type="button" 
                                        class="dropdown-item align-items-center base-form--postByUrl" 
                                        data-swal-title="Batalkan Perpanjangan UMP {{ $record->id_perpanjangan }} ?"
                                        data-url="{{ route($routes.'.cancel', $record) }}">
                                        <i class="mr-3 fa fa-trash text-danger"></i> {{ __('Batal') }}
                                    </button>
                                </div>       
                            </div>
                        </div>
                    @show
                </div>

            </div>
        </div>
        <!-- end of right -->

    </div>
    <!-- end of card bottom -->

    @show

@endsection

