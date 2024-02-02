@extends('layouts.pageSubmit')

@if ($page_action == "edit")
    @section('action', route($routes.'.update', $record->id))
@else
    @section('action', route($routes.'.reject', $record->id))
@endif

@section('card-body')
    @section('page-content')
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
                    @include('sgu.subs.card-1')                   
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
                    @include('sgu.subs.card-2')                   
                </div>
            </div>
        </div>
    </div>
    <!-- card -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body p-8">
                    @include('sgu.subs.card-2-extra')                   
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Besaran Amortisasi per Unit</h3>
                </div>
                <div class="card-body p-8">
                    @csrf
                    @include('sgu.subs.card-3')            
                </div>
                <div class="card-footer d-flex justify-content-between p-8">
                    @if ($page_action == "approval")
                    @if ($record->checkAction('approval', $perms))
                        @include('layouts.forms.btnBack')
                        @include('layouts.forms.btnDropdownApproval')
                        @include('layouts.forms.modalReject')
                        @include('layouts.forms.modalRevision')
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @show
    @endsection
    <!-- end of card -->
    <!-- end of card -->