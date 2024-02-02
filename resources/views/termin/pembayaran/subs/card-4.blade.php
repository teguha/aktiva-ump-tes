<div class="table-reponsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center width-40px">#</th>
                <th class="text-center">{{ __('Nominal') }}</th>
                <th class="text-center">{{ __('Pajak') }}</th>
                <th class="text-center">{{ __('Total') }}</th>
                <th class="text-center">{{ __('Status') }}</th>
                <th class="text-center valign-top width-30px">
                    <button type="button" class="btn btn-sm btn-icon btn-circle btn-primary add-ext-part"><i class="fa fa-plus"></i></button>
                </th>
            </tr>
        </thead>
        <tbody class="main-tbody">
            @if($record->details->count() == 0)
                <tr data-key="1">
                    <td class="text-center width-40px no">1</td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text"  name="details[1][nominal]" value="0"  class="form-control text-right  base-plugin--inputmask_currency nominal-input-currency" placeholder="{{ __('Nominal') }}">
                        </div>    
                    </td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" name="details[1][pajak]" value="0"  class="form-control text-right base-plugin--inputmask_currency pajak-input-currency" placeholder="{{ __('Pajak') }}">
                        </div>  
                    </td>
                    <td class="text-left parent-group">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rp
                                </span>
                            </div>
                            <input type="text" class="form-control text-right total-input-currency" placeholder="{{ __('Total') }}" disabled>
                        </div>
                    </td>
                    <td class="text-center width-120px">Belum dibayar</td>
                    <td class="text-center valign-top width-30px">
                        <button type="button" class="btn btn-sm btn-icon btn-circle btn-danger remove-ext-part">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @else
            @foreach ($record->details as $dd)
                @if($dd->status == 'Terbayar')
                    <tr data-key="{{ $loop->iteration }}">
                        <td class="text-center width-40px no">{{ $loop->iteration }}</td>
                        <td class="text-left parent-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input type="text" class="form-control text-right  base-plugin--inputmask_currency nominal-input-currency" placeholder="{{ __('Nominal') }}" value="{{number_format($dd->nominal)}}" disabled>
                            </div>    
                        </td>
                        <td class="text-left parent-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input type="text" class="form-control text-right base-plugin--inputmask_currency pajak-input-currency" placeholder="{{ __('Pajak') }}" disabled value="{{number_format($dd->pajak)}}">
                            </div>  
                        </td>
                        <td class="text-left parent-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input type="text" class="form-control text-right total-input-currency" placeholder="{{ __('Total') }}" value="{{ number_format($dd->total,0,',','.')  }}" disabled>
                            </div>
                        </td>
                        <td class="text-center width-120px">{{ $dd->status }}</td>
                        <td class="text-center valign-top width-30px">
                            @if($dd->status == "Belum dibayar")
                            <button type="button" class="btn btn-sm btn-icon btn-circle btn-danger remove-ext-part">
                                <i class="fa fa-trash"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                @else
                    <tr data-key="{{ $loop->iteration }}">
                        <td class="text-center width-40px no">{{ $loop->iteration }}</td>
                        <td class="text-left parent-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input type="text"  name="details[{{ $loop->iteration }}][nominal]"  class="form-control text-right  base-plugin--inputmask_currency nominal-input-currency" placeholder="{{ __('Nominal') }}" value="{{number_format($dd->nominal)}}" @if($dd->status == 'Terbayar') disabled @endif>
                            </div>    
                        </td>
                        <td class="text-left parent-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input type="text" name="details[{{ $loop->iteration }}][pajak]"  class="form-control text-right base-plugin--inputmask_currency pajak-input-currency" placeholder="{{ __('Pajak') }}" @if($dd->status == 'Terbayar') disabled @endif value="{{number_format($dd->pajak)}}">
                            </div>  
                        </td>
                        <td class="text-left parent-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input type="text" class="form-control text-right total-input-currency" placeholder="{{ __('Total') }}" value="{{ number_format($dd->total,0,',','.')  }}" disabled>
                            </div>
                        </td>
                        <td class="text-center width-120px">{{ $dd->status }}</td>
                        <td class="text-center valign-top width-30px">
                            @if($dd->status == "Belum dibayar")
                            <button type="button" class="btn btn-sm btn-icon btn-circle btn-danger remove-ext-part">
                                <i class="fa fa-trash"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            @endif
        </tbody>
        <tbody>
            <tr>
                <td colspan="2"></td>
                <td class="text-center parent-group" style="vertical-align:middle;">
                    <h4 class="text-bold" style="font-size:1rem; font-wight:600;">Total</h4>
                </td>
                <td class="text-left parent-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Rp
                            </span>
                        </div>
                        <input type="text" class="form-control text-right total_all_input" placeholder="{{ __('Total') }}" value="{{number_format($record->details()->sum('total'),0,',','.')}}" disabled>
                    </div>  
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!--  -->


@push('scripts')
<script>
    function formatCurrency(value){
        value = parseInt(value.replace(/^Rp/, '').replace(/\./g, ''));
        return value.toLocaleString('id-ID');
    }
</script>
<script>
    $('.content-page').ready(function(){
        $('.content-page').on('click', '.add-ext-part', function () {
            var me = $(this),
                tbody = me.closest('table').find('tbody.main-tbody').first(),
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
                    <td class="text-center width-120px">Belum dibayar</td>
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
				tbody = me.closest('table').find('>tbody.main-tbody').first(),
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

            // all sum
            var sum = 0;
            $('.total-input-currency').each(function(){
                sum += parseInt(this.value.replace(/\./g, ""));
            });
            total_all.val(formatCurrency(sum.toString()));
		});
    });
    $('.content-page').ready(function(){
        $('.content-page').on('change', 'tr .nominal-input-currency', function(){
            let nominal = $(this).val();
            let pajak   = $(this).closest('tr').find('.pajak-input-currency');
            let total   = $(this).closest('tr').find('.total-input-currency');
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
        $('.content-page').on('change', 'tr .pajak-input-currency', function(){
            let nominal = $(this).closest('tr').find('.nominal-input-currency').val();
            let pajak   = $(this).val();
            let total   = $(this).closest('tr').find('.total-input-currency');
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