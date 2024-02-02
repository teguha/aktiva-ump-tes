@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_submit" value="0">
    <input type="hidden" name="detail_id" value="0">
    <input type="hidden" name="pengajuan_pembelian_id" value="{{ $record->id }}">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Jenis Aktiva') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <select class="form-control base-plugin--select2-ajax" name="jenis_asset"
                        data-placeholder="{{ __('Jenis Aktiva') }}">
                        <option value="tangible">Tangible</option>
                        <option value="intangible">Intangible</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Aktiva') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input type="text" name="nama_aktiva" data-post="nama_aktiva" class="form-control" placeholder="{{ __('Nama Aktiva') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Vendor') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <select class="form-control base-plugin--select2" name="vendor_id" data-placeholder="Vendor">
                        <option disabled value="">Vendor</option>
                        @foreach ($vendors as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Merk') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input type="text" name="merk" class="form-control" placeholder="{{ __('Merk') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No. Seri/Tipe') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input type="text" name="no_seri" class="form-control" placeholder="{{ __('No. Seri/Tipe') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Lokasi Aktiva') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <select class="form-control base-plugin--select2-ajax" name="struct_id" data-url="{{ route('ajax.selectStruct', 'all') }}"
                    data-placeholder="{{ __('Lokasi Aktiva') }}">
                        <option disabled selected value="">Lokasi Aktiva</option>
                    </select>
                </div>
            </div>

        </div>
        <!-- right -->
        <div class="col-sm-12 col-md-6">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Jml Unit') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <input type="text" name="jumlah_unit_pembelian" class="form-control text-right base-plugin--inputmask_currency" placeholder="{{ __('Jml Unit') }}">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Unit
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Harga per Unit') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input type="text" name="harga_per_unit" class="form-control text-right  base-plugin--inputmask_currency" placeholder="{{ __('Harga per Unit') }}">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Total Harga') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input disabled type="text" name="total_harga" class="form-control text-right" placeholder="{{ __('Total Harga') }}">
                    </div>
                </div>
            </div>
            <div class="form-group row" id="intangible_asset" style="display: none">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Masa Pakai') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <input type="number" name="masa_pakai" class="form-control" placeholder="{{ __('Masa Pakai') }}">
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
                        <div class = "flex-fill">
                            <select class="base-plugin--select2-ajax form-control"
                                data-url="{{ route('ajax.selectMasaPenggunaan') }}"
                                data-post="masa_penggunaan"
                                name="masa_penggunaan"
                                data-placeholder="{{ __('Masa Penggunaan') }}">
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
                     <input
						type="text"
						name="tgl_pembelian"
						data-post="tgl_pembelian"
						class="form-control base-plugin--datepicker"
						data-options='@json([
                            "endDate" => now()->format('d/m/Y')
						])'
						placeholder="{{ __('Tgl Pembelian') }}">
                </div>
            </div>
            <div class="form-group row" id="tangible_asset">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Habis Masa Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        style="pointer-events: none;"
                        readonly
						name="habis_masa_depresiasi"
						data-post="habis_masa_depresiasi"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"startDate" => now()->format('d/m/Y')
						])'
						placeholder="{{ __('Habis Masa Depresiasi') }}"
                        tabindex="-1"
                        >
                </div>
            </div>
            <div class="form-group row" id="tangible_asset">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Mulai Masa Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input disabled style="pointer-events: none;"type="text" id="tgl_mulai_depresiasi" class="form-control" placeholder="{{ __('Mulai Masa Depresiasi') }}">
                </div>
            </div>
            <div class="form-group row" id="intangible_asset" style="display: none">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Habis Masa Amortisasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        readonly
                        style="pointer-events: none;"
                        type="text"
						name="habis_masa_amortisasi"
						data-post="habis_masa_amortisasi"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"startDate" => now()->format('d/m/Y')
						])'
						placeholder="{{ __('Habis Masa Amortisasi') }}"
                        >
                </div>
            </div>
            <div class="form-group row" id="intangible_asset" style="display: none" >
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Mulai Masa Amortisasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input disabled type="text" id="tgl_mulai_amortisasi" class="form-control" placeholder="{{ __('Mulai Masa Amortisasi') }}">
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
	$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl');

    $("select[name='jenis_asset']").on('change', function()
    {
        var jenis_aset = $(this).val();
        if(jenis_aset == "tangible"){
            $("div[id='tangible_asset']").show();
            $("div[id='intangible_asset']").hide();
        }
        else{
            $("div[id='tangible_asset']").hide();
            $("div[id='intangible_asset']").show();
        }
    })
    $("input[name='tgl_pembelian']").on('change', function()
    {
        if($(this).val){
            var date = $("input[name='tgl_pembelian']").val();
            moment.locale('id');
            var day = moment(date, "DD/MM/YYYY").date();
            if (day <= 15) {
                var result = moment(date, "DD/MM/YYYY").format("MMMM - YYYY");
            } else {
                var result = moment(date, "DD/MM/YYYY").add(1, "months").format("MMMM - YYYY");
                var result_date = moment(date, "DD/MM/YYYY").add(1, "months").format("d/m/Y");

            }
            $("#tgl_mulai_depresiasi").val(result);
            $("#tgl_mulai_amortisasi").val(result);
            var masa_penggunaan = $("select[data-post='masa_penggunaan']").find(':selected').text();;
            var masa_pakai = $("input[name='masa_pakai']").val();
            if(masa_pakai){
                var habis_masa = moment(date, "DD/MM/YYYY").add(masa_pakai, "months").subtract(1, "days").format("DD/MM/YYYY");
                $("input[name='habis_masa_amortisasi']").val(habis_masa);
            }
            if(masa_penggunaan){
                var habis_masa = moment(date, "DD/MM/YYYY").add(parseInt(masa_penggunaan), "years").subtract(1, "days").format("DD/MM/YYYY");
                $("input[name='habis_masa_depresiasi']").val(habis_masa);
            }
        }
    })
    $("input[name='masa_pakai']").on('change', function()
    {
        if($(this).val){
            var date = $("input[name='tgl_pembelian']").val();
            moment.locale('id');
            var masa_pakai = $(this).val();
            if(date){
                var habis_masa = moment(date, "DD/MM/YYYY").add(masa_pakai, "months").subtract(1, "days").format("DD/MM/YYYY");
                $("input[name='habis_masa_amortisasi']").val(habis_masa);
            }
        }
    })
    $("select[data-post='masa_penggunaan']").on('change', function()
    {
        if($(this).val){
            var date = $("input[name='tgl_pembelian']").val();
            moment.locale('id');
            var masa_penggunaan = $(this).val();
            if(date){
                var habis_masa = moment(date, "DD/MM/YYYY").add(masa_penggunaan, "years").subtract(1, "days").format("DD/MM/YYYY");
                $("input[name='habis_masa_depresiasi']").val(habis_masa);
            }
        }
    })
    $(document).ready(function() {
        // Serialize the object to a string
        var date = $("input[name='tgl_pembelian']").val();
        moment.locale('id');
        if(date){
            var day = moment(date, "DD/MM/YYYY").date();
            if (day <= 15) {
                var result = moment(date, "DD/MM/YYYY").format("MMMM - YYYY");
            } else {
                var result = moment(date, "DD/MM/YYYY").add(1, "months").format("MMMM - YYYY");
                var result_date = moment(date, "DD/MM/YYYY").add(1, "months").format("d/m/Y");
            }
            $("#tgl_mulai_depresiasi").val(result);
            $("#tgl_mulai_amortisasi").val(result);
        }
        var jenis_aset = $("select[name='jenis_asset']").val();
        if(jenis_aset == "tangible"){
            $("div[id='tangible_asset']").show();
            $("div[id='intangible_asset']").hide();
        }
        else{
            $("div[id='tangible_asset']").hide();
            $("div[id='intangible_asset']").show();
        }
        $("input[name='jumlah_unit_pembelian']").on('input', function() {
            var formattedValue = $(this).val();
            if(formattedValue != ""){
                var harga_per_unit = $("input[name='harga_per_unit']").val();
                if (harga_per_unit!=""){
                    harga_per_unit = parseInt(harga_per_unit.replace(/\./g, ""));
                    total_harga = harga_per_unit * parseInt(formattedValue.replace(/\./g, ""));
                    $("input[name='total_harga']").val(formatCurrency(total_harga.toString()));
                    console.log(176, total_harga);

                }
            }
        });
        $("input[name='harga_per_unit']").on('input', function() {
            var formattedValue = $(this).val();
            if(formattedValue != ""){
                var jumlah_unit_pembelian = $("input[name='jumlah_unit_pembelian']").val();
                if (jumlah_unit_pembelian!=""){
                    jumlah_unit_pembelian = parseInt(jumlah_unit_pembelian.replace(/\./g, ""));
                    total_harga = jumlah_unit_pembelian * parseInt(formattedValue.replace(/\./g, ""));
                    $("input[name='total_harga']").val(formatCurrency(total_harga.toString()));

                }
            }
        });
    });
</script>
@endpush

