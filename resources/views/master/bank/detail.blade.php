{{-- @extends('layouts.form') --}}
@extends('layouts.modal')

@if ($page_action == "create")
    @section('action', route($routes.'.store'))
@elseif ($page_action == "edit")
    @section('action', route($routes.'.update', $record->id))
@endif

{{-- @section('card-body') --}}
@section('modal-body')
    @if ($page_action == "edit")
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Bank') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="bank" class="form-control" placeholder="{{ __('Bank') }}"
                     {{$page_action == "show" ? "readonly" : ""}}
                    value="{{in_array($page_action, ["show", "edit"]) ? $record->bank : ""}}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Alamat') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <textarea name="alamat" class="form-control" placeholder="{{ __('Alamat') }}"
                   {{$page_action == "show" ? "readonly" : ""}}
                    rows="3">{{in_array($page_action, ["show", "edit"]) ? $record->alamat : ""}}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection

@if($page_action == "show")
    @section('buttons')
    @stop
@endif
