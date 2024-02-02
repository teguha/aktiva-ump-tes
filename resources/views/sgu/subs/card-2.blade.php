@method('PUT')
@csrf
<div class="row">
    {{-- <form action="" method="POST" class="row"> --}}
        <!-- left -->
        <div class="col-sm-12 col-md-6">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Lokasi Sewa') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input value = "{{$record->rent_location ?? ""}}" {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}} type="text" name="rent_location" class="form-control" placeholder="{{ __('Lokasi Sewa') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Jangka Waktu Sewa') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <input value = "{{$record->rent_time_period ?? ""}}" {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}} type="number" name="rent_time_period" class="form-control" placeholder="{{ __('Jangka Waktu Sewa') }}">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Bulan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Periode Awal Sewa') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}}
                        value="{{ $record->rent_start_date ? date('d/m/Y', strtotime($record->rent_start_date)) : ""}}"
						type="text"
						name="rent_start_date"
						data-post="rent_start_date"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"endDate" => now()->format('d/m/Y')
						])'
						placeholder="{{ __('Periode Awal Sewa') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Periode Akhir Sewa') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        disabled
                        value="{{ $record->rent_end_date ? date('d/m/Y', strtotime($record->rent_end_date)) : ""}}"
						type="text"
						name="rent_end_date"
						data-post="rent_end_date"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"startDate" => now()->format('d/mm/Y')
						])'
						placeholder="{{ __('Periode Akhir Sewa') }}">
                </div>
            </div>

        </div>
        <!-- right -->
        <div class="col-sm-12 col-md-6">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Deposit') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input value="{{$record->deposit ? number_format($record->deposit, 0, ",", ".") : "" }}" {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}} type="text" name="deposit" class="form-control text-right" placeholder="{{ __('Deposit') }}">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Harga Sewa') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input value="{{$record->rent_cost ? number_format($record->rent_cost, 0, ",", ".") : "" }}" {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}} type="text" name="rent_cost" class="form-control text-right" placeholder="{{ __('Harga Sewa (Termasuk Pajak)') }}">
                    </div>
                </div>
            </div>
            <div class="form-group row" >
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Mulai Masa Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input disabled style="pointer-events: none;"type="text" name="depreciation_start_date" class="form-control" placeholder="{{ __('Mulai Masa Depresiasi') }}">
                </div>
            </div>
            {{-- <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Mulai Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        disabled
                        value="{{ $record->depreciation_start_date ? date('d/m/Y', strtotime($record->depreciation_start_date)) : ""}}"
						type="text"
						name="depreciation_start_date"
						data-post="depreciation_start_date"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"startDate" => now()->format('d/mm/Y')
						])'
						placeholder="{{ __('Mulai Depresiasi') }}">
                </div>
            </div> --}}
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Habis Masa Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        value="{{$record?($record->depreciation_end_date ? date('d/m/Y', strtotime($record->depreciation_end_date)) : ""):""}}"
                        {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}}
                        style="pointer-events: none;"
                        readonly
						type="text"
						name="depreciation_end_date"
						data-post="depreciation_end_date"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"startDate" => now()->format('d/m/Y')
						])'
						placeholder="{{ __('Habis Masa Depresiasi') }}"
                        >
                </div>
            </div>
            {{-- <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Akhir Depresiasi') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        disabled
                        value="{{ $record->depreciation_end_date ? date('d/m/Y', strtotime($record->depreciation_end_date)) : ""}}"
						type="text"
						name="depreciation_end_date"
						data-post="depreciation_end_date"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"startDate" => now()->format('d/mm/Y')
						])'
						placeholder="{{ __('Akhir Depresiasi') }}">
                </div>
            </div> --}}
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pembayaran') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                     <input
                        {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}}
                        value="{{ $record->payment_date ? date('d/m/Y', strtotime($record->payment_date)) : ""}}"
						type="text"
						name="payment_date"
						data-post="payment_date"
						class="form-control base-plugin--datepicker"
						data-options='@json([
							"endDate" => now()->format('d/m/Y')
						])'
						placeholder="{{ __('Tgl Pembayaran') }}">
                </div>
            </div>
        </div>
    {{-- </form> --}}
</div>


@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
    $("input[name='rent_time_period']").on('change', function()
    {
        if($(this).val){
            var date = $("input[name='rent_start_date']").val();
            moment.locale('id');
            var rent_time_period = $(this).val();
            if(date){
                var habis_masa = moment(date, "DD/MM/YYYY").add(rent_time_period, "month").subtract(1, "days").format("MMMM YYYY");
                $("input[name='depreciation_end_date']").val(habis_masa);
                $("input[name='rent_end_date']").val(habis_masa);
            }
        }
    })
    $("input[name='rent_start_date']").on('change', function()
    {
        if($(this).val){
            var date = $("input[name='rent_start_date']").val();
            moment.locale('id');
            var day = moment(date, "DD/MM/YYYY").date();
            if (day <= 15) {
                var result = moment(date, "DD/MM/YYYY").format("MMMM YYYY");
            } else {
                var result = moment(date, "DD/MM/YYYY").add(1, "months").format("MMMM YYYY");
            }
            $("input[name='depreciation_start_date']").val(result);
            var masa_pakai = $("input[name='rent_time_period']").val();
            if(masa_pakai){
                var habis_masa = moment(date, "DD/MM/YYYY").add(masa_pakai, "months").subtract(1, "days").format("MMMM YYYY");
                $("input[name='depreciation_end_date']").val(habis_masa);
                $("input[name='rent_end_date']").val(habis_masa);
            }
        }
    })
    $(document).ready(function() {
        var date = $("input[name='rent_start_date']").val();
        moment.locale('id');
        if(date){
            var day = moment(date, "DD/MM/YYYY").date();
            if (day <= 15) {
                var result = moment(date, "DD/MM/YYYY").format("MMMM YYYY");
            } else {
                var result = moment(date, "DD/MM/YYYY").add(1, "months").format("MMMM YYYY");
            }
            $("input[name='depreciation_start_date']").val(result);
            var masa_pakai = $("input[name='rent_time_period']").val();
            if(masa_pakai){
                var habis_masa = moment(date, "DD/MM/YYYY").add(masa_pakai, "months").subtract(1, "days").format("MMMM YYYY");
                $("input[name='depreciation_end_date']").val(habis_masa);
                $("input[name='rent_end_date']").val(habis_masa);
            }
        }
        $("input[name='rent_cost']").on('input', function() {
            const formattedValue = formatCurrency($(this).val());
            if(formattedValue == "NaN"){
                $(this).val('');
            }
            else {
                $(this).val(formattedValue);
            }
        });
        $("input[name='deposit']").on('input', function() {
            const formattedValue = formatCurrency($(this).val());
            if(formattedValue == "NaN"){
                $(this).val('');
            }
            else {
                $(this).val(formattedValue);
            }
        });

    });

</script>
@endpush
