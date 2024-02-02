@extends('layouts.modal')

@section('modal-body')
    @method('PATCH')
	<input type="hidden" name="is_submit" value="0">
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Unit Kerja') }}</label>
        <div class="col-9 parent-group">
            <input class="form-control" placeholder="{{ __('Unit Kerja') }}" value="{{ $record->struct->name }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Aktiva') }}</label>
        <div class="col-9 parent-group">
            <input type="hidden" name="aktiva_id" value="{{ $detail->aktiva_id }}">
            <select id="aktiva_id" class="form-control base-plugin--select2-ajax aktiva_id"
                data-url="{{ route('ajax.selectAktiva', [
                    'pemeriksaan_id' => $record->id,
                    'struct_id' => $record->struct->id,
                ]) }}"
                data-placeholder="{{ __('Pilih Aktiva') }}"
                disabled>
                <option selected value="{{ $detail->aktiva_id }}">{{ $detail->aktiva->code }} - {{ $detail->aktiva->pembelianAktivaDetail->nama_aktiva }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Merk') }}</label>
        <div class="col-9 parent-group">
            <input id="tempMerk" class="form-control" disabled value="{{ $detail->aktiva->pembelianAktivaDetail->merk }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('No Seri / Tipe') }}</label>
        <div class="col-9 parent-group">
            <input id="tempNoSeri" class="form-control" disabled value="{{ $detail->aktiva->pembelianAktivaDetail->no_seri }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Kondisi') }}</label>
        <div class="col-9 parent-group">
            <select class="form-control base-plugin--select2-ajax"
                data-placeholder="{{ __('Pilih Kondisi') }}" disabled>
                <option {{ $detail->condition ==='Baik' ?'selected':'' }} value="Baik">Baik</option>
                <option {{ $detail->condition ==='Rusak' ?'selected':'' }} value="Rusak">Rusak</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-9 parent-group">
            <textarea class="form-control" disabled placeholder="{{ __('Keterangan') }}">{!! $detail->condition !!}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Lampiran') }}</label>
        <div class="col-9 parent-group">
            <div class="custom-file">
                @foreach ($detail->files as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 py-2 px-3"
                        role="alert">
                        <div class="alert-icon">
                            <i class="{{ $file->file_icon }}"></i>
                        </div>
                        <div class="alert-text text-left">
                            <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                            <div>Uploaded File:</div>
                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                {{ $file->file_name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection

@section('buttons')
@endsection
