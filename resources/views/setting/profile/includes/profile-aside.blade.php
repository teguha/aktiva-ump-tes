<div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
	<div class="card card-custom">
		<div class="card-body pt-15">

			<div class="text-center mb-10">
				<div class="symbol symbol-60 symbol-circle symbol-xl-90 shadow">
                    <img src="{{ asset(auth()->user()->image_path) }}" alt="Image">
					<i class="symbol-badge symbol-badge-bottom bg-success"></i>
				</div>
				<h4 class="font-weight-bold my-2">{{ auth()->user()->name }}</h4>
				<div class="text-muted mb-2">{{ auth()->user()->roles_impoded }}</div>
				<span class="label label-light-warning label-inline font-weight-bold label-lg">{{ __('Aktif') }}</span>
			</div>

			<a href="{{ route($routes.'.index') }}" 
				class="btn btn-hover-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block base-content--replace {{ ($tab == 'profile') ? 'active' : '' }}">
				{{ __('Profil') }}
			</a>
			<a href="{{ route($routes.'.notification') }}" 
				class="btn btn-hover-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block base-content--replace {{ ($tab == 'notification') ? 'active' : '' }}">
				{{ __('Notifikasi') }}
			</a>
			<a href="{{ route($routes.'.activity') }}" 
				class="btn btn-hover-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block base-content--replace {{ ($tab == 'activity') ? 'active' : '' }}">
				{{ __('Aktifitas') }}
			</a>
			<a href="{{ route($routes.'.changePassword') }}" 
				class="btn btn-hover-light-primary font-weight-bold py-3 px-6 mb-2 text-center btn-block base-content--replace {{ ($tab == 'changePassword') ? 'active' : '' }}">
				{{ __('Ubah Password') }}
			</a>
			
		</div>
	</div>
</div>