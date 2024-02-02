@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
    @method('PATCH')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" name="nama" value="{{ $record->nama }}" class="form-control" placeholder="{{ __('Nama') }}">
        </div>
    </div>
@endsection
