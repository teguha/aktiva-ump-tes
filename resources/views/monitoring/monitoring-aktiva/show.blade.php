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
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Detil Pembayaran Termin</h3>
                </div>
                <div class="card-body p-8">
                    @csrf
                    @include('termin.subs.card-4-show')            
                </div>
            </div>
        </div>
    </div>
    <!-- end of layouts form -->

   

    @show

@endsection