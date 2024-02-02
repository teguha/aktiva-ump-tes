@extends('layouts.page')

@section('page')
<div class="d-flex flex-row">
    @include($views.'.includes.profile-aside', ['tab' => 'profile'])

    <div class="flex-row-fluid ml-lg-8">
        <div class="card card-custom gutter-b">

            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">{{ __('Profil') }}</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Informasi Pribadi') }}</span>
                </div>
            </div>

            <form action="{{ route($routes.'.updateProfile') }}" method="post" autocomplete="off">
                @csrf
                @method('POST')

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Foto') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="image-input image-input-outline" id="kt_profile_avatar"
                                style="background-image: url({{ asset('assets/media/users/blank.png')  }})">
                                <img class="image-input-wrapper show"
                                    style="background-image: url({{ asset(auth()->user()->image_path)}})">
                                <label
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change" data-toggle="tooltip" title=""
                                    data-original-title="Ganti foto">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" id="srcfile" name="image" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="image">
                                </label>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="cancel" data-toggle="tooltip" title=""
                                    data-original-title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Nama') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text"
                                value="{{ auth()->user()->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Role') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text"
                                value="{{ auth()->user()->roles_imploded }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Struktur') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text"
                                value="{{ auth()->user()->struct->name ?? '-' }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Jabatan') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text"
                                value="{{ auth()->user()->position->name ?? '-' }}" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9 col-xl-6">
                            <h5 class="font-weight-bold mt-10 mb-6">{{ __('Informasi Kontak') }}</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Telepon') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-phone"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="phone"
                                    value="{{ auth()->user()->phone }}" placeholder="{{ __('Telepon') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label text-right">{{ __('Email') }}</label>
                        <div class="col-lg-9 col-xl-6">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="email"
                                    value="{{ auth()->user()->email }}" placeholder="{{ __('Email') }}">
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

@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function(e) {
	          $('.show').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#srcfile").change(function() {
        readURL(this);
    });
</script>
@endpush