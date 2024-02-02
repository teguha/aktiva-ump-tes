<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-4 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="struct" data-url="{{ route('ajax.selectStruct', 'all') }}"
                    data-placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                    <option value="{{ $record->struct ?? auth()->user()->position->location->id }}" selected {{ in_array($page_action, ['show', 'approval' ]) }}>{{
                        $record->struct ? $record->struct->name : auth()->user()->position->location->name }}</option>
                </select>
                <input type="hidden" name="struct" value = "{{ $record->struct ? $record->struct : auth()->user()->position->location->id }}">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('PIC Staff Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-4 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('NIK') }}"
                    value="{{ $pic->nik }}" disabled>
            </div>
            <div class="col-sm-12 col-md-4 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('Nama') }}"
                    value="{{ $pic->username }}" disabled>
            </div>
            <input type="hidden" name="pic_staff" value="{{$pic->id}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Kepala Departemen Terkait') }}</label>
            <div class="col-sm-12 col-md-4 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('NIK') }}"
                    value="{{ $kepala_dept->nik ?? '' }}" disabled>
            </div>
            <div class="col-sm-12 col-md-4 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('Nama') }}"
                    value="{{ $kepala_dept->username ?? '' }}" disabled>
            </div>
            <input type="hidden" name="pic_kepala_dept" value="{{$kepala_dept->id ?? ''}}">
            <input type="hidden" name="pic_kepala_dept_role" value="{{$kepala_dept->roles[0]->id ?? ''}}">
        </div>
    </div>
</div>
