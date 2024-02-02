<div class="btn-group dropdown">
	<button type="button" class="btn btn-primary dropdown-toggle"
		data-toggle="dropdown"
		aria-haspopup="true"
		aria-expanded="false">
		<i class="mr-1 fa fa-save"></i>
		{{ __('Import') }}
	</button>
	<div class="dropdown-menu dropdown-menu-right">
		<a href="{{ route($routes.'.import') }}"
			class="dropdown-item align-items-center base-modal--render">
			<i class="mr-2 fa fa-upload text-primary"></i>
			{{ __('Import') }}
		</a>
		<div class="dropdown-divider"></div>
		<a href="{{ route($routes.'.import', ['download' => 'template']) }}"
			target="_blank"
			class="dropdown-item align-items-center">
			<i class="mr-2 fa fa-download text-primary"></i>
			{{ __('Download Template') }}
		</a>
	</div>
</div>
