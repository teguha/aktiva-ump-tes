@extends('layouts.modal')

@section('action', route($routes . '.store'))

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="name" class="form-control name" placeholder="{{ __('Nama') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('NIK') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" name="nik" class="form-control" placeholder="{{ __('NIK') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Username') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="username" class="form-control" placeholder="{{ __('Username') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Email') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('No Handphone') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" name="phone" class="form-control" data-placeholder="No Handphone"
                placeholder="{{ __('No Handphone') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Struktur Organisasi') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="location_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectStruct', 'parent_position') }}"
                placeholder="{{ __('Struktur Organisasi') }}">
                <option value="">{{ __('Pilih Struktur Organisasi') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jabatan') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="position_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectPosition', 'all') }}" placeholder="{{ __('Jabatan') }}">
                <option value="">{{ __('Pilih jabatan') }}</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Hak Akses') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="roles[]" class="form-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectRole', 'all') }}" placeholder="{{ __('Hak Akses') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Password') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Konfirmasi Password') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="password" name="password_confirmation" class="form-control"
                placeholder="{{ __('Konfirmasi Password') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Status') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="status" class="form-control base-plugin--select2" placeholder="{{ __('Status') }}">
                <option value="active">Aktif</option>
                <option value="nonactive">Non Aktif</option>
            </select>
        </div>
    </div>
@endsection
