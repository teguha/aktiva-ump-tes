<div class="table-reponsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center width-40px">#</th>
                <th class="text-center">{{ __('Nominal') }}</th>
                <th class="text-center">{{ __('Pajak') }}</th>
                <th class="text-center">{{ __('Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($record->details as $dd)
                <tr data-key="{{ $loop->iteration }}">
                    <td class="text-center width-40px no">{{ $loop->iteration }}</td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" name="details[{{ $loop->iteration }}][nominal]"  class="form-control text-right  base-plugin--inputmask_currency nominal-input-currency" placeholder="{{ __('Nominal') }}" value="{{number_format($dd->nominal)}}" disabled>
                        </div>    
                    </td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" name="details[{{ $loop->iteration }}][pajak]"  class="form-control text-right base-plugin--inputmask_currency pajak-input-currency" placeholder="{{ __('Pajak') }}" value="{{number_format($dd->pajak)}}" disabled>
                        </div>  
                    </td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" class="form-control text-right total-input-currency" placeholder="{{ __('Total') }}" value="{{ number_format($dd->total) }}" disabled>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!--  -->


@push('scripts')
<script>
    $('.content-page').ready(function(){
        $('.content-page').on('click', '.add-ext-part', function () {
            var me = $(this),
                tbody = me.closest('table').find('tbody').first(),
                key = tbody.find('tr').length ? (tbody.find('tr').last().data('key') + 1) : 1;

            var template = `
                <tr data-key="`+key+`">
                    <td class="text-center width-40px no">`+key+`</td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" name="details[`+key+`][nominal]"  class="form-control text-right base-plugin--inputmask_currency nominal-input-currency" placeholder="{{ __('Nominal') }}" value="0">
                        </div>    
                    </td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" name="details[`+key+`][pajak]"  class="form-control text-right base-plugin--inputmask_currency pajak-input-currency" placeholder="{{ __('Pajak') }}" value="0">
                        </div>
                    </td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" name="details[`+key+`][total]"  class="form-control text-right total-input-currency" placeholder="{{ __('Total') }}" value="0" disabled>
                        </div>
                    </td>
                    <td class="text-center valign-top width-30px">
                        <button type="button" class="btn btn-sm btn-icon btn-circle btn-danger remove-ext-part">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            tbody.append(template);
            BasePlugin.init();

        });
    });

    $('.content-page').ready(function(){
        $('.content-page').on('click', '.remove-ext-part', function (e) {
			e.preventDefault();

			var me = $(this),
				tbody = me.closest('table').find('>tbody').first(),
				key = tbody.find('>tr').length ? (parseInt(tbody.find('>tr').last().data('key')) + 1) : 1;

			me.closest('tr').remove();
			tbody.find('.no').each(function (i) {
				$(this).html(i+1);
			});
			tbody.find('.remove-detail').prop('disabled', false);
			if (tbody.find('.remove-detail').length == 1) {
				tbody.find('.remove-detail').prop('disabled', true);
			}
			BasePlugin.init();
		});
    });
    $('.content-page').ready(function(){
        $('.content-page').on('change', '.nominal-input-currency', function(){
            let nominal = $(this).val();
            let pajak   = $('.nominal-input-currency').closest('tr').find('.pajak-input-currency');
            let total   = $('.nominal-input-currency').closest('tr').find('.total-input-currency');
            const formattedValue = formatCurrency($(this).val());

            // 
            nominal_int = parseInt(nominal.replace(/\,/g, ""));
            pajak_int = parseInt(pajak.val().replace(/\,/g, ""));
            total_int = nominal_int + pajak_int;
            total.val(formatCurrency(total_int.toString()));
            console.log(176, nominal);
        });
    });
    $('.content-page').ready(function(){
        $('.content-page').on('change', '.pajak-input-currency', function(){
            let nominal = $('.nominal-input-currency').closest('tr').find('.nominal-input-currency').val();
            let pajak   = $(this).val();
            let total   = $('.nominal-input-currency').closest('tr').find('.total-input-currency');
            nominal_int = parseInt(nominal.replace(/\,/g, ""));
            pajak_int = parseInt(pajak.replace(/\,/g, ""));
            total_int = nominal_int + pajak_int;
            total.val(formatCurrency(total_int.toString()));
            console.log(176, nominal);
        });
    });
</script>

@endpush