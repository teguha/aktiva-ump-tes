@extends('layouts.lists')

@section('filters')
	<div class="row">
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <input type="text" class="form-control filter-control" data-post="code" placeholder="{{ __('ID Pengajuan SGU') }}">
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <div class="input-group">
                <input name="date_start"
                    class="form-control filter-control base-plugin--datepicker date_start"
                    placeholder="{{ __('Mulai') }}"
                    data-orientation="bottom"
                    data-post="date_start"
                    >
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                </div>
                <input name="date_end"
                    class="form-control filter-control base-plugin--datepicker date_end"
                    placeholder="{{ __('Selesai') }}"
                    data-orientation="bottom"
                    data-post="date_end"
                    >
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax" name="ref_org_struct_id"
                data-url="{{ route('ajax.selectStruct', 'all') }}"
                data-post="work_unit_id"
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
            <option value="draft">Draft</option>
            <option value="waiting authorization">Menunggu Otorisasi</option>
            <option value="waiting verification">Menunggu Verifikasi</option>
            <option value="waiting payment">Menunggu Pembayaran</option>
            <option value="revision">Revisi</option>
            <option value="paid">Terbayar</option>
            <option value="cancelled">Batal</option>
            </select>
        </div>
    </div>
@endsection
