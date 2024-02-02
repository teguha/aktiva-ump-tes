<table id="dataFilters" class="width-full">
    <tbody>
        <tr>
            <td class="pb-2 valign-top td-filter-reset width-80px">
                <div class="reset-filter mr-1 hide">
                    <button class="btn btn-secondary btn-icon width-full reset button" data-toggle="tooltip"
                        data-original-title="Reset Filter"><i class="fas fa-sync"></i></button>
                </div>
                <div class="label-filter mr-1">
                    <button class="btn btn-secondary btn-icon width-full filter button" data-toggle="tooltip"
                        data-original-title="Filter"><i class="fas fa-filter"></i></button>
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-3 pb-2">
                        <input type="text" class="form-control filter-control" data-post="name"
                            placeholder="{{ __('Kode & Nama Aktiva') }}">
                    </div>
                </div>
            </td>
            <td class="text-right td-btn-create width-200px">
                @if (request()->route()->getName() !=
                    $routes . '.show')
                    @if ($record->checkAction('edit', $perms))
                        @include('layouts.forms.btnAddModal', [
                            'urlAdd' => route($routes . '.detailCreate', $record->id),
                        ])
                    @endif
                @endif
            </td>
        </tr>
    </tbody>
</table>
<div class="table-responsive">
    @if (isset($tableStruct['datatable_1']))
        <table id="datatable_1" class="table table-bordered is-datatable" style="width: 100%;"
            data-url="{{ $tableStruct['url'] }}" data-paging="{{ $paging ?? true }}" data-info="{{ $info ?? true }}">
            <thead>
                <tr>
                    @foreach ($tableStruct['datatable_1'] as $struct)
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
            </tbody>
        </table>
    @endif
</div>
