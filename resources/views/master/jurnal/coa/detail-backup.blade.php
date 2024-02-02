@extends('layouts.pageSubmit')

@if ($page_action == "create")
    @section('action', route($routes.'.store'))
@elseif ($page_action == "edit")
    @section('action', route($routes.'.update', $record->id))
@endif

@section('card-body')
    @section('page-content')
    @if ($page_action == "edit")
        @method('PUT')
    @endif
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
                    @include('master.jurnal.coa.subs.card-1')
                    @section('buttons')
                    <div class="d-flex justify-content-between">
                        @include('layouts.forms.btnBack')
                        @if (in_array($page_action, ["edit", "create"]))
                            @include('layouts.forms.btnSubmitPage')
                        @endif
                    </div>
                    @show                   
                </div>
            </div>
        </div>
    </div>
    <!-- end of header -->

@show
@endsection