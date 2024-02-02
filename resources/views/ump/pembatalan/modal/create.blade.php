@extends('layouts.modal')

@section('action', route($routes . '.store'))

@section('modal-body')
    @method('POST')

    @section('buttons')
    @show
    <table id="dataFilters" class="width-full">
        <tbody>
            <tr>
                <td class="pb-2 valign-top td-filter-reset width-80px">
                    <div class="reset-filter mr-1 hide">
                        <button class="btn btn-secondary btn-icon width-full reset button" data-toggle="tooltip" data-original-title="Reset Filter"><i class="fas fa-sync"></i></button>
                    </div>
                    <div class="label-filter mr-1">
                        <button class="btn btn-secondary btn-icon width-full filter button" data-toggle="tooltip" data-original-title="Filter"><i class="fas fa-filter"></i></button>
                    </div>
                </td>
                <td>
                    <input type="hidden" class="form-control filter-control" data-post="ids" value="{{ request()->get('ids') }}">
                   <div class="row">
                        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
                            <input type="text" class="form-control filter-control" data-post="code" placeholder="{{ __('ID Pengajuan UMP') }}">
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
                            <input type="text" class="form-control filter-control" data-post="nama_asset" placeholder="{{ __('Nama Asset') }}">
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
                            <select class="form-control filter-control base-plugin--select2-ajax" name="struct_id"
                                data-url="{{ route('ajax.selectStruct', 'all') }}"
                                data-post="struct_id"
                                data-placeholder="{{ __('Unit Kerja') }}"
                            >
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
                            <input type="text" class="form-control filter-control" data-post="pic" placeholder="{{ __('PIC Unit Kerja') }}">
                        </div>
                    </div>
                </td>
                <td class="text-right td-btn-create text-nowrap">
                    @yield('buttons-before')
                    @section('buttons')
                        @include('layouts.forms.btnAdd')
                    @show
                    @yield('buttons-after')
                </td>
            </tr>
        </tbody>
    </table>
<div class="table-responsive">
        @if (isset($tableStruct['datatable_2']))
            <table id="datatable_2" class="table table-bordered is-datatable" style="width: 100%;"
                data-url="{{ $tableStruct['url'] }}" data-paging="{{ $paging ?? true }}"
                data-info="{{ $info ?? true }}">
                <thead>
                    <tr>
                        @foreach ($tableStruct['datatable_2'] as $struct)
                            <th class="text-center v-middle" data-columns-name="{{ $struct['name'] ?? '' }}"
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
                    @yield('tableBody')
                </tbody>
            </table>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            BaseList.render('#datatable_2');

            $(document).on('shown.bs.dropdown', '#datatable_2', function (e) {
		        // The .dropdown container
		        var me = $(e.target);

		        // Find the actual .dropdown-menu
		        var dropdown = me.find('.dropdown-menu');
		        if (dropdown.length) {
		            // Save a reference to it, so we can find it after we've attached it to the body
		            me.data('dropdown-menu', dropdown);
		        } else {
		            dropdown = me.data('dropdown-menu');
		        }

		        dropdown.css('top', (me.offset().top + me.outerHeight()) + 'px');
		        dropdown.css('left', me.offset().left + 'px');
		        dropdown.css('position', 'absolute');
		        dropdown.css('display', 'block');
                dropdown.css('z-index', '9999');
		        dropdown.appendTo('#content-page');

		    });

		    $(document).on('hide.bs.dropdown', '#datatable_2', function (e) {
		        // Hide the dropdown menu bound to this button
		        $(e.target).data('dropdown-menu').css('display', 'none');
		    });
        });

    </script>
@endpush
