<div class="card card-custom mb-5">
    <div class="card-body padding-20">
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
                            @section('buttons')
                                {{-- @include('layouts.forms.btnAdd') --}}
                            @show
                            @yield('buttons-after')
                        </td>
                    </tr>
                </tbody>
            </table>
        @show
        <div class="mt-2">
            <ul class="nav nav-tabs" role="tablist">
                @if ($tableStruct['tabs'] === 'default')
                    @php
                        $tableStruct['tabs'] = [
                            [
                                'title' => 'TO DO',
                                'table_id' => 'datatable_1',
                                'table_url' => route($routes . '.grid'),
                                'badge' => false,
                                'active' => !in_array(request()->tab, [2, 3]) ? true : false,
                            ],
                            [
                                'title' => 'IN PROGRESS',
                                'table_id' => 'datatable_2',
                                'table_url' => route($routes . '.grid', ['tab' => 2]),
                                'badge' => false,
                                'active' => in_array(request()->tab, [2]) ? true : false,
                            ],
                            [
                                'title' => 'COMPLETE',
                                'table_id' => 'datatable_3',
                                'table_url' => route($routes . '.grid', ['tab' => 3]),
                                'badge' => false,
                                'active' => in_array(request()->tab, [3]) ? true : false,
                            ],
                        ];
                    @endphp
                @endif
                @foreach ($tableStruct['tabs'] as $tab)
                    <li class="nav-item mx-0">
                        <a href="#tab-{{ $tab['num'] }}" data-toggle="tab"
                            data-badge="{{ $tab['badge'] ? 'true' : 'false' }}"
                            class="nav-link tab-list {{ $loop->first ? 'nav-link-danger' : ($loop->last ? 'nav-link-success' : 'nav-link-info') }} {{ $tab['active'] ? 'active' : '' }}">
                            <span class="f-w-500">{{ $tab['title'] }}</span>
                            @if ($tab['badge'])
                                <span class="label label-info tab-badge ml-2 label-inline">0</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content">
            @foreach ($tableStruct['tabs'] as $tab)
                <div class="tab-pane fade {{ $tab['active'] ? 'active show' : '' }}"
                    id="tab-{{ $tab['num'] }}">
                    <div class="table-responsive">
                        @if (isset($tableStruct[$tab['table_id']]))
                            <table id="{{ $tab['table_id'] }}"
                                class="table table-bordered table-hover is-datatable"
                                data-url="{{ $tab['table_url'] }}"
                                data-badge="{{ $tab['badge'] ? 'true' : 'false' }}"
                                data-tab-list="#tab-{{ $tab['num'] }}" style="width: 100%">
                                <thead>
                                    <tr>
                                        @foreach ($tableStruct[$tab['table_id']] as $struct)
                                            <th class="text-center v-middle"
                                                data-columns-name="{{ $struct['name'] ?? '' }}"
                                                data-columns-data="{{ $struct['data'] ?? '' }}"
                                                data-columns-label="{{ $struct['label'] ?? '' }}"
                                                data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                                                data-columns-width="{{ $struct['width'] ?? '' }}"
                                                data-columns-class-name="{{ $struct['className'] ?? '' }}"
                                                style="{{ isset($struct['width']) ? 'width: ' . $struct['width'] : '' }}">
                                                {{ $struct['label'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @yield('tableBody')
                                </tbody>
                            </table>
                        @else
                            <h5 class="text-center">Empty Tab Content</h5>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
