@extends('layouts.page')

@section('page')
    <div class="d-flex flex-row">
        @include($views.'.includes.profile-aside', ['tab' => 'changePassword'])

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom gutter-b">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">{{ __($title) }}</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Informasi Pribadi') }}</span>
                    </div>
                </div>

                <form action="{{ route($routes.'.updatePassword') }}" method="post" autocomplete="off">
                    @csrf
                    @method('POST')

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Password Lama') }}</label>
                            <div class="col-lg-9 col-xl-6 parent-group">
                                <div class="input-group input-group-lg input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="la la-key"></i>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control" name="old_password" placeholder="{{ __('Password Lama') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Password Baru') }}</label>
                            <div class="col-lg-9 col-xl-6 parent-group">
                                <div class="input-group input-group-lg input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="la la-key"></i>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control" name="new_password" placeholder="{{ __('Password Baru') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Konfirmasi Password Baru') }}</label>
                            <div class="col-lg-9 col-xl-6 parent-group">
                                <div class="input-group input-group-lg input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="la la-key"></i>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="{{ __('Konfirmasi Password Baru') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        @include('layouts.forms.btnSubmitPage')
                    </div>
                </form>

            </div>
        </div>
        
    </div>
@endsection