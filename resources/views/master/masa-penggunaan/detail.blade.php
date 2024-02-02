@extends('layouts.modal')

@if ($page_action == "create")
    @section('action', route($routes.'.store'))
@elseif ($page_action == "edit")
    @section('action', route($routes.'.update', $record->id))
@endif

@section('modal-body')
    @section('page-content')
    @if ($page_action == "edit")
        @method('PUT')
    @endif
    <!-- header -->
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Masa Penggunaan Aset Tangible') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <div class="input-group">
                        <input type="number" name="masa_penggunaan" class="form-control" placeholder="{{ __('Masa Penggunaan Aset Tangible') }}"
                       {{$page_action == "show" ? "readonly" : ""}}
                        value="{{$page_action != "create" ? $record->masa_penggunaan : ""}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bolder">Tahun</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end of header -->

@show
@endsection