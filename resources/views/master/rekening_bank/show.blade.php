@extends('layouts.modal')

@section('modal-body')
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Nama Pemilik') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->pemilik->name }}" class="form-control" placeholder="{{ __('Nama Pemilik') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('No Rekening') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->no_rekening }}" class="form-control" placeholder="{{ __('No Rekening') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('KCP') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->kcp }}" class="form-control" placeholder="{{ __('KCP') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('Bank') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" value="{{ $record->bank->name }}" class="form-control" placeholder="{{ __('Bank') }}" disabled>
        </div>
    </div>
@endsection

@section('buttons')
@endsection
