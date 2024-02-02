@extends('layouts.modal')

@section('action', route($routes.'.importSave'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-md-4 col-form-label">{{ __('Import Excel') }}</label>
		<div class="col-md-8 parent-group">
			<div class="custom-file">
				<input type="hidden" 
					name="uploads[uploaded]" 
					class="uploaded" 
					value="">
			    <input type="file"  
			    	class="custom-file-input base-form--save-temp-files"
			    	data-name="uploads"
			    	data-container="parent-group"
			    	data-max-size="20024"
			    	data-max-file="1"
			    	accept=".xlsx">
			    <label class="custom-file-label" for="file">Choose File</label>
			</div>
			<div class="form-text text-muted">*Pastikan file sesuai dengan template terbaru</div>
		</div>
	</div>
@endsection