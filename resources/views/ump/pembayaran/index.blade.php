@extends('layouts.lists')

@section('filters')
	<div class="row">
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <input type="text" class="form-control filter-control" data-post="id_pengajuan" placeholder="{{ __('ID Pengajuan Aktiva/SGU') }}">
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <div class="input-group">
                <input name="tgl_pengajuan_start"
                    class="form-control filter-control base-plugin--datepicker date_start"
                    placeholder="{{ __('Mulai') }}"
                    data-orientation="bottom"
                    data-options='@json([
                        "format" => "d/m/yyyy"
                    ])'
                    data-post="tgl_pengajuan_start"
                    >
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                </div>
                <input name="tgl_pengajuan_end"
                    class="form-control filter-control base-plugin--datepicker date_end"
                    placeholder="{{ __('Selesai') }}"
                    data-orientation="bottom"
                    data-options='@json([
                        "format" => "d/m/yyyy"
                    ])'
                    data-post="tgl_pengajuan_end"
                    >
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax" name="unit_kerja"
                data-url="{{ route('ajax.selectStruct', 'all') }}"
                data-post="unit_kerja"
                data-placeholder="{{ __('Unit Kerja') }}"
            >
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2" name="status"
                data-post="status"
                data-placeholder="{{ __('Status') }}"
            >
            <option value="">Pilih Salah Satu</option>
            <option value="new">Baru</option>
            <option value="draft">Draft</option>
            <option value="waiting approval">Menunggu Otorisasi</option>
            <option value="waiting verification">Menunggu Verifikasi</option>
            <option value="pay remaining">Transfer Selisih</option>
            <option value="completed">Selesai</option>
            <option value="revision">Revisi</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons')
@endsection
