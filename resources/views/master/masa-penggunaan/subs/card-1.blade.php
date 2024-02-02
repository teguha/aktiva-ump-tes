<div class="mb-8">
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Masa Penggunaan Aset Tangible') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <div class="input-group">
                <input type="number" name="masa_penggunaan" class="form-control" placeholder="{{ __('Masa Penggunaan Aset Tangible') }}"
                {{in_array($page_action, ["edit", "create"])  ? "" :"disabled"}}
                value="{{$page_action != "create" ? $record->masa_penggunaan : ""}}">
                <div class="input-group-prepend">
                    <span class="input-group-text font-weight-bolder">Tahun</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Status') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            @if (!in_array($page_action,["edit", "create"]))
                @switch($record->status)
                    @case('1')
                        <span class="badge badge-warning text-white">Draft</span>
                        @break
                    @case('2')
                        <span class="badge badge-success">Aktif</span>
                        @break
                    @case('3')
                        <span class="badge badge-secondary">Nonaktif</span>
                        @break
                    @default
                        <span class="badge badge-primary">{{ ucwords($record->status)}}</span>
                        @break
                @endswitch
            @else
                <select class="form-control base-plugin--select2-ajax" name="status" data-placeholder="Status">
                    <option disabled {{$page_action == "create" ? "selected" : ""}} value="">Status</option>
                    @if($page_action == "create")
                        <option {{$page_action == "create" ? "" : ($record->status == "1" ? "selected" : "")}} value="1">Draft</option>
                    @endif
                        <option {{$page_action == "create" ? "" : ($record->status == "2" ? "selected" : "")}} value="2">Aktif</option>
                        <option {{$page_action == "create" ? "" : ($record->status == "3" ? "selected" : "")}} value="3">Nonaktif</option>
                </select>
            @endif
        </div>
    </div>
</div>