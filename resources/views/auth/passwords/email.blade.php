@extends('layouts.auth')

@section('content')
		<!--begin::Forgot-->
		<div class="login-form login-forgot d-block">
			<div class="text-center mb-10 mb-lg-20">
				<h3 class="font-size-h1">{{ __('Reset Password') }}</h3>
				<p class="text-muted font-weight-bold">Enter your email to reset your password</p>
			</div>
			<!--begin::Form-->
			@if (session('status'))
			<div class="alert alert-success" role="alert">
				{{ session('status') }}
			</div>
			@endif
			<form method="POST" class="form" action="{{ route('password.email') }}">
				@csrf

				<div class="form-group">
					<input class="form-control form-control-solid h-auto py-5 px-6 @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="off" />
					@error('email')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				
				<div class="form-group d-flex flex-wrap flex-center">
					<button type="submit" id="" class="btn btn-primary font-weight-bold my-3 mx-4">{{ __('Send Password Reset Link') }}</button>
					<a href="{{ route('login') }}" class="btn btn-light-primary font-weight-bold my-3 mx-4">Cancel</a>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Forgot-->
@endsection