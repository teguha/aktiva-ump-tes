{{-- @extends('layouts.form') --}}
@extends('layouts.modal')

@section('action', route($routes . '.store'))

{{-- @section('card-body') --}}
@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_submit" value="0">
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('ID Pengajuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan') }}">
                </div>
            </div>
        </div>
        
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Tgl Pengajuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="date" class="form-control base-plugin--datepicker"
                        {{-- data-post="date" --}}
                        placeholder="{{ __('Tgl Pengajuan') }}"
                        data-date-end-date="{{ now() }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Unit Kerja') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    @if($user = auth()->user())
                    <select name="struct_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                        data-placeholder="{{ __('Pilih Unit Kerja') }}">
				        <option value="">{{ __('Unit Kerja') }}</option>
                        @if($user->position_id != NULL)
                        <option value="{{ $user->position->location->id }}" selected>
                            {{ $user->position->location->name }}
                        </option>
				        @endif
                    </select>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-5 pr-0">
                    <label class="col-form-label">{{ __('Cara Pembayaran') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select class="form-control base-plugin--select2" name="cara_pembayaran" data-placeholder="Cara Pembayaran">
                        <option value="bertahap">Bertahap</option>
                        <option value="sekaligus">Sekaligus</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

