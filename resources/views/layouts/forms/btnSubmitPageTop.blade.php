<button type="button"
	class="btn btn-primary base-modal--render"
	data-modal-url="{{ $urlSubmit ?? (!empty($record) && \Route::has($routes.'.submit') ? route($routes.'.submit', $record->id) : '') }}"
	data-modal-position="modal-dialog-centered">
	<i class="fa fa-save mr-1"></i>
	{{ __('Submit') }}
</button>
