<div class="col-12">
	<div class="row card-progress-wrapper" data-url="{{ route($routes.'.progress') }}">
		@php
			$cards = collect(json_decode(json_encode([
				[
					'name' => 'pembelian',
					'title' => 'Pembelian Aktiva',
					'icon' => 'fas fa-paper-plane',
				],
				[
					'name' => 'sgu',
					'title' => 'Sewa Guna Usaha',
					'icon' => 'fa fa-tags',
				],
				[
					'name' => 'pelepasan',
					'title' => 'Pelepasan Aktiva',
					'icon' => 'fas fa-bookmark',
				],
				[
					'name' => 'mutasi',
					'title' => 'Pengajuan Mutasi',
					'icon' => 'fas fa-id-card',
				],
			])));
		@endphp
		@foreach ($cards as $card)
			<div class="col-xl-3 col-md-6 col-sm-12">
				<div class="card card-custom gutter-b card-stretch bg-white"
					data-name="{{ $card->name }}">
					<div class="card-body">
						<div class="d-flex flex-wrap align-items-center py-1">
							{{-- <div class="symbol symbol-40 symbol-light-{{ $card->color }} mr-5">
								<span class="symbol-label shadow">
									<i class="{{ $card->icon }} align-self-center text-{{ $card->color }} font-size-h5"></i>
								</span>
							</div> --}}
							<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
								<div class="text-dark font-weight-bolder font-size-h5">
									{{ __($card->title) }}
								</div>
								<div class="text-muted font-weight-bold font-size-lg">
									<div class="d-flex justify-content-between">
										<span class="text-nowrap">Selesai/Total</span>
										<span class="text-nowrap">
											<span class="completed">0</span>/<span class="total">0</span>
										</span>
									</div>
								</div>
							</div>
							<div class="d-flex flex-column w-100 mt-5">
								<div class="text-muted mr-2 font-size-lg font-weight-bolder pb-3">
									<div class="d-flex justify-content-between">
										<span class="percent-text">0%</span>
									</div>
								</div>
								<div class="progress progress-xs w-100">
									<div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
</div>

@push('scripts')
	<script>
		$(function () {
			initCardProgress();
		});

		var initCardProgress = function () {
			var wrapper = $('.card-progress-wrapper');
			$.ajax({
				url: wrapper.data('url'),
				type: 'POST',
				data: {
					_token: BaseUtil.getToken(),
				},
				success: function (resp) {
					if (resp.data && resp.data.length) {
						$.each(resp.data, function (i, item) {
							console.log(item)
							var card = wrapper.find('.card[data-name="'+item.name+'"]');
							card.find('.completed').html(item.completed);
							card.find('.total').html(item.total);
							card.find('.percent-text').html(item.percent+'%');
							card.find('.progress-bar').css('width', item.percent+'%');
							card.find('.progress-bar').attr('aria-valuenow', item.percent);
							if (item.percent == 100){
								card.find('.progress-bar').removeClass('bg-danger');
								card.find('.progress-bar').addClass('bg-success');
							} else{
								card.find('.progress-bar').removeClass('bg-success');
								card.find('.progress-bar').addClass('bg-danger');
							}
						});
					}
				},
				error: function (resp) {
					console.log(resp)
					console.log("aaksk")
				},
			});
		}
	</script>
@endpush
