<form action="@yield('action')" method="POST" autocomplete="@yield('autocomplete', 'off')">
	<div class="modal-header">
		<h4 class="modal-title">@yield('modal-title', $title)</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<i aria-hidden="true" class="ki ki-close"></i>
		</button>
	</div>
	<div class="modal-body pt-3">
		@csrf
		@yield('modal-body')
	</div>
	@section('buttons')
		<div class="modal-footer">
			@section('modal-footer')
				@include('layouts.forms.btnSubmitModal')
			@show
		</div>
	@show
</form>

@stack('scripts')
