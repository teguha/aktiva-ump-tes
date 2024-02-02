<div class="btn-group dropdown">
	<button type="button" class="btn btn-primary dropdown-toggle"
		data-toggle="dropdown"
		aria-haspopup="true"
		aria-expanded="false">
		<i class="mr-1 fa fa-save"></i>
		{{ __('Export') }}
	</button>
	<div class="dropdown-menu dropdown-menu-right">
		<a href="{{ route($routes.'.export', ['download' => 'excel']) }}"
			target="_blank"
			class="dropdown-item align-items-center">
			<i class="mr-2 fa fa-download text-primary"></i>
			{{ __('Excel') }}
		</a>
		<div class="dropdown-divider"></div>
		<a href="{{ route($routes.'.export', ['download' => 'pdf']) }}"
			target="_blank"
			class="dropdown-item align-items-center">
			<i class="mr-2 fa fa-download text-primary"></i>
			{{ __('Pdf') }}
		</a>
	</div>
</div>
