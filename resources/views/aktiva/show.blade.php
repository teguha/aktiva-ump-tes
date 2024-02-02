@extends('layouts.page')

@section('card-body')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Jenis Aktiva') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <select class="form-control base-plugin--select2-ajax" name="jenis_asset"
                        data-placeholder="{{ __('Jenis Aktiva') }}" disabled>
                        <option value="tangible"
                            {{ $record->pembelianAktivaDetail->jenis_asset == 'tangible' ? 'selected' : '' }}>Tangible
                        </option>
                        <option value="intangible"
                            {{ $record->pembelianAktivaDetail->jenis_asset == 'intangible' ? 'selected' : '' }}>Intangible
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Aktiva') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input name="nama_aktiva" data-post="nama_aktiva" class="form-control"
                        placeholder="{{ __('Nama Aktiva') }}" value="{{ $record->pembelianAktivaDetail->nama_aktiva }}"
                        disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Vendor') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input name="vendor" data-post="vendor" class="form-control" placeholder="{{ __('Vendor') }}"
                        value="{{ $record->pembelianAktivaDetail->vendor->name }}" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Merk') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input name="merk" class="form-control" placeholder="{{ __('Merk') }}"
                        value="{{ $record->pembelianAktivaDetail->merk }}" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No. Seri/Tipe') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input class="form-control" value="{{ $record->pembelianAktivaDetail->no_seri }}" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Lokasi Aktiva') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input class="form-control" value="{{ $record->struct->name }}" disabled>
                </div>
            </div>
        </div>
        <!-- right -->
        <div class="col-sm-12 col-md-6">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Harga per Unit') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input name="harga_per_unit" class="form-control base-plugin--inputmask_currency text-right"
                            placeholder="{{ __('Harga per Unit') }}"
                            value="{{ $record->pembelianAktivaDetail->harga_per_unit }}" disabled>
                    </div>
                </div>
            </div>
            <div class="form-group row" id="intangible_asset" style="display: none">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Masa Pakai') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <input type="number" name="masa_pakai" class="form-control" placeholder="{{ __('Masa Pakai') }}"
                            value="{{ $record->pembelianAktivaDetail->masa_pakai }}" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Bulan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row" id="tangible_asset">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Masa Penggunaan') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="flex-fill">
                            <select class="base-plugin--select2-ajax form-control"
                                data-url="{{ route('ajax.selectMasaPenggunaan') }}" data-post="masa_penggunaan"
                                name="masa_penggunaan" data-placeholder="{{ __('Masa Penggunaan') }}" disabled>
                                @if ($record->pembelianAktivaDetail->masa_penggunaan)
                                    <option value={{ $record->pembelianAktivaDetail->masa_penggunaan }} selected>
                                        {{ $record->pembelianAktivaDetail->masa_penggunaan }}
                                    </option>
                                @endif
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Tahun
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pembelian') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input class="form-control base-plugin--datepicker"
                        value="{{ $record->pembelianAktivaDetail->tgl_pembelian->format('d/m/Y') }}" disabled>
                </div>
            </div>
            <div class="form-group row" id="tangible_asset">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Habis Masa Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input class="form-control base-plugin--datepicker"
                        @if ($record->pembelianAktivaDetail->habis_masa_depresiasi) value="{{ $record->pembelianAktivaDetail->habis_masa_depresiasi->format('d/m/Y') }}" @endif
                        disabled>
                </div>
            </div>
            <div class="form-group row" id="tangible_asset">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Mulai Masa Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input disabled class="form-control" disabled>
                </div>
            </div>
            <div class="form-group row" id="intangible_asset" style="display: none">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Habis Masa Amortisasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input class="form-control base-plugin--datepicker"
                        @if ($record->pembelianAktivaDetail->habis_masa_amortisasi) value="{{ $record->pembelianAktivaDetail->habis_masa_amortisasi->format('d/m/Y') }}" @endif
                        disabled>
                </div>
            </div>
            <div class="form-group row" id="intangible_asset" style="display: none">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Mulai Masa Amortisasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input disabled class="form-control" disabled>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table-bordered table-hover is-datatable table" style="width: 100%;"
            data-paging="{{ $paging ?? true }}" ">
                            <thead>
                                <tr>
                                    {{-- @foreach ($tableStruct['datatable_1'] as $struct) --}}
                                        <th class="v-middle text-center"
                                            data-columns-sortable="true">
                                            #
                                        </th>
                                        <th class="v-middle text-center"
                                            data-columns-sortable="true">
                                            Tahun
                                        </th>
                                        <th class="v-middle text-center"
                                        data-columns-sortable="true">
                                        Nilai Buku (Rp)
                                        </th>
                                        <th class="v-middle text-center"
                                            data-columns-sortable="true">
                                            Masa Manfaat (Bln)
                                        </th>
                                             @if ($record->jenis_asset == 'tangible')
            <th class="v-middle text-center" data-columns-sortable="true">
                Depresiasi (Rp)
            </th>
            <th class="v-middle text-center" data-columns-sortable="true">
                Depresiasi per Bulan (Rp)
            </th>
        @elseif($record->jenis_asset == 'intangible')
            <th class="v-middle text-center" data-columns-sortable="true">
                Amortisasi (Rp)
            </th>
            <th class="v-middle text-center" data-columns-sortable="true">
                Amortisasi per Bulan (Rp)
            </th>
            @endif
            {{-- @endforeach --}}
            </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i <= count($tableData) - 1; $i++)
                    <tr>
                        <td class="text-center">{{ $tableData[$i]['no'] }}</td>
                        <td class="text-center">{{ $tableData[$i]['tahun'] }}</td>
                        <td class="text-right">{{ $tableData[$i]['nilai_buku'] }}</td>
                        <td class="text-center">{{ $tableData[$i]['masa_manfaat'] }}</td>
                        @if ($record->jenis_asset == 'tangible')
                            <td class="text-right">{{ $tableData[$i]['depresiasi'] }}</td>
                            <td class="text-right">{{ $tableData[$i]['depresiasi_per_bulan'] }}</td>
                        @elseif($record->jenis_asset == 'intangible')
                            <td class="text-right">{{ $tableData[$i]['amortisasi'] }}</td>
                            <td class="text-right">{{ $tableData[$i]['amortisasi_per_bulan'] }}</td>
                        @endif
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <hr>
    <h3>Riwayat Mutasi</h3>
    <div class="table-responsive">
        @if (isset($tableStruct['datatable_1']))
            <table id="datatable_1" class="table-bordered is-datatable table" style="width: 100%;"
                data-url="{{ $tableStruct['url'] }}" data-paging="{{ $paging ?? true }}"
                data-info="{{ $info ?? true }}">
                <thead>
                    <tr>
                        @foreach ($tableStruct['datatable_1'] as $struct)
                            <th class="v-middle text-center" data-columns-name="{{ $struct['name'] ?? '' }}"
                                data-columns-data="{{ $struct['data'] ?? '' }}"
                                data-columns-label="{{ $struct['label'] ?? '' }}"
                                data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                data-columns-width="{{ $struct['width'] ?? '' }}"
                                data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] . '; ' : '' }}">
                                {{ $struct['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        @endif
    </div>

@endsection

@section('buttons')
@endsection
