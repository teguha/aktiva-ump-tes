<div class="form-group row">
    <label class="col-md-2 col-form-label">{{ __('Kalimat Pembuka') }}</label>
    <div class="col-md-10 parent-group">
        <textarea name="sentence_start" data-height="120" class="form-control base-plugin--summernote"
            placeholder="{{ __('Kalimat Pembuka') }}" {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}}>@if(!$record->sentence_start)<p>Biaya Operasional ini dibuat pada tanggal {{ now()->translatedFormat('d F Y')}} dengan pengajuan sebagai berikut:</p>@else {!! $record->sentence_start !!} @endif</textarea>
    </div>
</div>
<div class="form-group row">
        <label class="col-md-2 col-form-label">{{ __('Kalimat Penutup') }}</label>
        <div class="col-md-10 parent-group">
            <textarea name="sentence_end" data-height="120" class="form-control base-plugin--summernote"
                placeholder="{{ __('Kalimat Penutu[') }}" {{in_array($page_action, ["show", "approval"]) ? "disabled" : ""}}>@if(!$record->sentence_end)<p>Demikian Biaya Operasional ini dibuat untuk dipertimbangkan sebaik-baiknya dan penuh tanggung jawab.</p>@else {!! $record->sentence_end !!} @endif</textarea>
        </div>
    </div>