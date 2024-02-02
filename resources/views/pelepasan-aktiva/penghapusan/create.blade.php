@extends('layouts.modal')

@section('action', route($routes . '.store'))

@section('modal-body')
    @method('POST')
	<!-- <input type="hidden" name="is_submit" value="0"> -->
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('ID Penghapusan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="code" class="form-control" placeholder="{{ __('ID Penghapusan') }}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Tgl Penghapusan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="date" class="form-control base-plugin--datepicker"
                        placeholder="{{ __('Tgl Penghapusan') }}"
                        data-date-end-date="{{ now() }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Unit Kerja') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    @if (auth()->user()->position)
                        <input value="{{$struct->name}}" disabled type="text" name="struct_name" class="form-control">
                        <input value="{{$struct->id}}" hidden type="text" name="struct_id" class="form-control">
                    @else
                        <select name="struct_id" class="form-control base-plugin--select2-ajax"
                        data-url="{{ route('ajax.selectStruct', 'all') }}"
                            data-placeholder="{{ __('Pilih Unit Kerja') }}">
                        </select>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

