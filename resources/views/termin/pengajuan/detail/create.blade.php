@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_submit" value="0">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Termin') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input type="number" name="no_termin" data-post="no_termin" class="form-control" placeholder="{{ __('Termin') }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nominal') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input type="text"  name="nominal" value="0"  class="form-control text-right  base-plugin--inputmask_currency nominal-input-currency" placeholder="{{ __('Nominal') }}">
                    </div>  
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Pajak') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input type="text" name="pajak" value="0"  class="form-control text-right base-plugin--inputmask_currency pajak-input-currency" placeholder="{{ __('Pajak') }}">
                    </div> 
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Total') }}</label>
                <div class="col-sm-12 col-md-8 parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input type="text" class="form-control text-right total-input-currency" placeholder="{{ __('Total') }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
    $('.content-page').ready(function(){
        $('.content-page').on('change', '.nominal-input-currency', function(){
            let nominal = $(this).val();
            let pajak   = $('.pajak-input-currency');
            let total   = $('.total-input-currency');
            let total_all   = $('.total_all_input');
            const formattedValue = formatCurrency($(this).val());

            // 
            nominal_int = parseInt(nominal.replace(/\./g, ""));
            pajak_int = parseInt(pajak.val().replace(/\./g, ""));
            total_int = nominal_int + pajak_int;
            total.val(formatCurrency(total_int.toString()));
            console.log(176, nominal);

            var sum = 0;
            $('.total-input-currency').each(function(){
                sum += parseInt(this.value.replace(/\./g, ""));
            });
            total_all.val(formatCurrency(sum.toString()));

        });
    });
    $('.content-page').ready(function(){
        $('.content-page').on('change', '.pajak-input-currency', function(){
            let nominal = $('.nominal-input-currency').val();
            let pajak   = $(this).val();
            let total   = $('.total-input-currency');
            let total_all   = $('.total_all_input');
            nominal_int = parseInt(nominal.replace(/\./g, ""));
            pajak_int = parseInt(pajak.replace(/\./g, ""));
            total_int = nominal_int + pajak_int;
            total.val(formatCurrency(total_int.toString()));
            console.log(176, nominal);

            // all sum
            var sum = 0;
            $('.total-input-currency').each(function(){
                sum += parseInt(this.value.replace(/\./g, ""));
            });
            total_all.val(formatCurrency(sum.toString()));
        });
    });
</script>
@endpush

