@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="name" class="form-control name" placeholder="{{ __('Nama') }}"
                value="{{ $record->name }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('NIK') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" name="nik" value="{{ $record->nik }}" class="form-control"
                placeholder="{{ __('NIK') }}" value="{{ $record->name }}" {{$record->username=="admin" ? "disabled" : ""}}>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Username') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="text" name="username" class="form-control" placeholder="{{ __('Username') }}" {{$record->username=="admin" ? "disabled" : ""}}
                value="{{ $record->username }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Email') }}</label>
        <div class="col-sm-8 parent-group">
            <input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" {{$record->username=="admin" ? "disabled" : ""}}
                value="{{ $record->email }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label">{{ __('No Handphone') }}</label>
        <div class="col-md-8 parent-group">
            <input type="text" name="phone" value="{{ $record->phone }}" class="form-control"
                data-placeholder="No Handphone" placeholder="{{ __('No Handphone') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Struktur Organisasi') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="location_id" class="form-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectStruct', 'parent_position') }}" {{$record->username=="admin" ? "disabled" : ""}}
                placeholder="{{ __('Struktur Organisasi') }}">
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if (isset($record->position->location_id))
                    <option value="{{ $record->position->location_id }}" selected>{{ $record->position->location->name }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Jabatan') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="position_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ route('ajax.selectPosition', 'all') }}" placeholder="{{ __('Jabatan') }}" {{$record->username=="admin" ? "disabled" : ""}}>
                    <option value="">{{ __('Pilih Salah Satu') }}</option>
                    @if ($record->position_id)
                        <option value="{{ $record->position_id }}" selected>{{ $record->position->name }}</option>
                    @endif
                </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Hak Akses') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="roles[]" class="form-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectRole', 'all') }}" placeholder="{{ __('Hak Akses') }}" {{$record->username=="admin" ? "disabled" : ""}}>
                <option value="">{{ __('Pilih Salah Satu') }}</option>
                @if ($record->roles)
                    @foreach ($record->roles as $val)
                        <option value="{{ $val->id }}" selected>{{ $val->name }}</option>
                    @endforeach
                @endif
            </select>
            @if ($record->username == "admin")
                <input type="hidden" name="roles[]" value="1">
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">{{ __('Status') }}</label>
        <div class="col-sm-8 parent-group">
            <select name="status" class="form-control base-plugin--select2" placeholder="{{ __('Status') }}" {{$record->username=="admin" ? "disabled" : ""}}>
                <option value="active">Aktif</option>
                <option value="nonactive">Non Aktif</option>
            </select>
        </div>
    </div>
@endsection
