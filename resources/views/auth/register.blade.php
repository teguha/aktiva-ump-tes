@extends('layouts.auth')
@section('content')
<!--begin::Signup-->
<div class="login-form login-signup d-block">
    <div class="text-center mb-10 mb-lg-20">
        <h3 class="font-size-h1">Sign Up</h3>
        <p class="text-muted font-weight-bold">Enter your details to create your account</p>
    </div>
    <!--begin::Form-->
    <form class="form" novalidate="novalidate" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-5 px-6 @error('name') is-invalid @enderror" type="text" placeholder="Full Name" name="name" value="{{ old('email') }}" required autocomplete="off" autofocus />
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-5 px-6 @error('email') is-invalid @enderror" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="off" />
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-5 px-6 @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" value="{{ old('email') }}" autocomplete="off" />
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Confirm password" name="password_confirmation" value="{{ old('email') }}" autocomplete="off" />
        </div>
        <div class="form-group d-flex flex-wrap flex-center">
            <button type="submit" class="btn btn-primary font-weight-bold my-3 mx-4">{{ __('Register') }}</button>
            <a href="{{ route('login') }}" class="btn btn-light-primary font-weight-bold my-3 mx-4">Cancel</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Signup-->
@endsection