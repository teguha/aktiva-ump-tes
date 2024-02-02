<div class="mb-8">
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Kategori') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="text" name="kategori" class="form-control" placeholder="{{ __('Kategori') }}"
                disabled value="{{$record->kategori}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Template') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="text" name="nama_template" class="form-control" placeholder="{{ __('Nama Template') }}"
                disabled value="{{$record->nama_template}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <textarea type="text" name="deskripsi" class="form-control" placeholder="{{ __('Deskripsi') }}" {{$page_action == "show" ? "disabled" : ""}}  rows="3">{{ $record->deskripsi ?? "" }}</textarea>
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
                @endswitch
            @else
                <select class="form-control base-plugin--select2-ajax" name="status" data-placeholder="Status">
                    <option disabled {{$page_action == "create" ? "selected" : ""}} value="">Status</option>
                    <option {{$page_action == "create" ? "" : ($record->status == "1" ? "selected" : "disabled")}} value="1">Draft</option>
                    <option {{$page_action == "create" ? "" : ($record->status == "2" ? "selected" : "")}} value="2">Aktif</option>
                </select>
            @endif
        </div>
    </div>
</div>