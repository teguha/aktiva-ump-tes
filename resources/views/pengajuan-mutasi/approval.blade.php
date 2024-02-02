@extends('layouts.pageSubmit')

@section('card-body')
    @section('page-content')
    @method('PATCH')
    @csrf
    <!-- header -->
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
                    @include('pengajuan-mutasi.subs.card-1-show')                   
                </div>
            </div>
        </div>
    </div>
    <!-- end of header -->

    <!-- card -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body p-8">
                    @csrf
                    @include('pengajuan-mutasi.subs.card-2')
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        @if ($record->checkAction('approval', $perms))
                            @include('layouts.forms.btnBack')
                            @include('layouts.forms.btnDropdownApproval')
                            @include('layouts.forms.modalReject')
                            @include('layouts.forms.modalRevision')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end of card -->

    <!-- end of card bottom -->
@show
@endsection
