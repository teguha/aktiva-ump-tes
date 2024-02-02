@extends('layouts.app')

@section('title', $title)

@section('content')
	@section('content-header')
		@include('layouts.base.subheader')
	@show
	@section('content-body')
    <form action="@yield('action', '#')" method="post" autocomplete="@yield('autocomplete', 'off')">
		<div class="d-flex flex-column-fluid">
			<div class="{{ $container ?? 'container-fluid' }}">
                <div class="row">

                    <div class="col-sm-12">

                        @yield('page-start')
                        @section('page')
                            @csrf
                            @yield('method')
                            @yield('page-content')
                        @show
                        @yield('page-end')
                    </div>

                </div>
			</div>
		</div>
    </form>
	@show
    @yield('another_element')
@endsection
