@extends('layouts.errors')

@section('content')
	<div class="d-flex flex-column flex-root">
		<!--begin::Error-->
		<div class="error error-3 d-flex flex-row-fluid bgi-size-cover bgi-position-center" 
			style="background-image: url('{{ asset('assets/media/error/bg3.jpg')  }}');">
			<div class="px-10 px-md-30 py-10 py-md-0 d-flex flex-column justify-content-md-center">
				<p class="display-4 font-weight-boldest text-white mb-12">403 | FORBIDDEN</p>
				<p class="font-size-h2 line-height-md">Forbidden Access to this resource on the server is denied!</p>
				<form action="<?= route('logout') ?>" method="POST">
				    @csrf
				    @method('POST')
				    <button type="submit" class="btn btn-light-primary font-weight-bold">Click here to Logout</button>
				</form>
			</div>
		</div>
	</div>
@endsection