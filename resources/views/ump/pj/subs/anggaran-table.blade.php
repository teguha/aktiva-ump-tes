@section('dataFilters')
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
                    @section('filters')
                        {!! $filters ?? '' !!}
                    @show
                </td>
                <td class="text-right td-btn-create text-nowrap">
                    @yield('buttons-before')
                    @if($page_action == "edit")
                        @include('layouts.forms.btnAdd')
                    @endif
                    @yield('buttons-after')
                </td>
            </tr>
        </tbody>
    </table>
    @show
    <div class="table-responsive"> 
    @if(isset($tableStruct['datatable_1']))
        <table id="datatable_1" class="table table-bordered table-hover is-datatable" style="width: 100%;" data-url="{{ isset($tableStruct['url']) ? $tableStruct['url'] : route($routes.'.grid') }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
            <thead>
                <tr>
                    @foreach ($tableStruct['datatable_1'] as $struct)
                        <th class="text-center v-middle"
                            data-columns-name="{{ $struct['name'] ?? '' }}"
                            data-columns-data="{{ $struct['data'] ?? '' }}"
                            data-columns-label="{{ $struct['label'] ?? '' }}"
                            data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                            data-columns-width="{{ $struct['width'] ?? '' }}"
                            data-columns-class-name="{{ $struct['className'] ?? '' }}"
                            style="{{ isset($struct['width']) ? 'width: '.$struct['width'].'; ' : '' }}">
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
    @yield('card-bottom-table')