@extends('layouts.app')

@section('title', $title)

@section('content')
	@section('content-header')
		@include('layouts.base.subheader')
	@show
	@section('content-body')
    <form action="@yield('action', '#')" method="POST"  data-req-method="@yield('req-method')" id="searchform" autocomplete="@yield('autocomplete', 'off')">

		<div class="d-flex flex-column-fluid">
			<div class="{{ $container ?? 'container-fluid' }}">
                <div class="row">

                    <div class="col-sm-12">

                        @yield('page-start')
                        @section('page')
                            @csrf
                            @yield('page-content')
                        @show
                        @yield('page-end')
                    </div>

                </div>
			</div>
		</div>
    </form>
	@show
@endsection