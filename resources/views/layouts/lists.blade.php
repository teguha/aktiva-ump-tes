@extends('layouts.app')

@section('title', __($title))

@section('content')

@section('content-header')
    @include('layouts.base.subheader')
@show

@section('content-body')
    <div class="d-flex flex-column-fluid">
        <div class="{{ empty($container) ? 'container-fluid' : $container }}">
            @yield('start-list')
            <div class="row">
                <div class="col-lg-12">
                    @includeWhen(empty($tableStruct['tabs']), 'layouts.partials.datatable')
                    @includeWhen(!empty($tableStruct['tabs']), 'layouts.partials.datatables')
                </div>
            </div>
            @yield('end-list')
        </div>
    </div>
@show
@endsection
