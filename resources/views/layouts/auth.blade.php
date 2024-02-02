@if (request()->header('Base-Replace-Content'))
	<script>
		window.location.reload();
	</script>
@else
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8" />
        <title>{{ !empty($title) ? $title.' | '.config('app.name') : config('app.name') }}</title>
        <meta name="debug" content="{{ config('app.debug') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('/') }}">
        <meta name="replace" content="1">
        <meta name="author" content="PT Pragma Informatika" />
        <meta name="description" content="Aplication by PT Pragma Informatika" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <link rel="shortcut icon" href="{{ asset(config('base.logo.favicon')) }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/fonts/poppins/all.css') }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/plugins.bundle.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/theme.bundle.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/theme.skins.bundle.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/base.bundle.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/modules.bundle.css')) }}">
        <style>
            .patern-box {
                width: 1030px;
                height: 1030px;
                border-radius: 60px;
                background-color: #e1e2fb;
                position: fixed;
                top: -240px;
                left: -240px;
                transform: rotate(27deg);
            }
        </style>
	</head>
	<body id="kt_body" class="header-fixed header-mobile-fixed page-loading">
		<div class="no-body-clear page-loader page-loader-default fade-out">
            <div class="blockui">
                <span>Please wait...</span>
                <span><div class="spinner spinner-primary"></div></span>
            </div>
        </div>

		<div class="d-flex flex-column flex-root align-items-center justify-content-center">
		    <div class="patern-box"></div>
		    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row" id="kt_login">
		        <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
		            
		            <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
		                <div class="card rounded-xl shadow">
		                    <div class="card-body p-0">
		                        <div class="d-flex">
		                            <div class="p-10">
		                                @yield('content')
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>

		            <div class="d-flex flex-column align-items-center p-5">
		                <div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">
		                	{{ config('base.app.name') }} - {{ config('base.app.version') }}
		                </div>
		                <div class="d-flex order-1 order-sm-2 my-2 text-dark-50">
		                    © {{ config('base.app.copyright') }}
		                </div>
		            </div>

		        </div>
		    </div>
		</div>

		<script src="{{ asset(mix('assets/js/plugins.bundle.js')) }}"></script>
        {{-- <script src="{{ asset(mix('assets/js/theme.config.js')) }}"></script> --}}
        <script src="{{ asset(mix('assets/js/theme.bundle.js')) }}"></script>
        <script src="{{ asset(mix('assets/js/base.bundle.js')) }}"></script>
        <script src="{{ asset(mix('assets/js/modules.bundle.js')) }}"></script>
		@stack('scripts')
	</body>
	</html>
@endif