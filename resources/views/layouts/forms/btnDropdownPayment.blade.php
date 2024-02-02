<div class="btn-group dropdown">
	<button type="button"
		class="btn btn-info dropdown-toggle"
		data-toggle="dropdown"
		aria-haspopup="true"
		aria-expanded="false">
		<i class="mr-1 fa fa-save"></i> {{ __('Bayar') }}
	</button>
	<div class="dropdown-menu dropdown-menu-right">
		<button type="button"
			class="dropdown-item align-items-center base-form--approveByUrl"
			@if(in_array($module,["pengajuan_pembelian.pengajuan", "pelepasan-aktiva.penghapusan", "sgu","mutasi-aktiva.pengajuan"]))
			data-swal-title = "{{in_array($module, ['pengajuan_pembelian.pengajuan', 'pelepasan-aktiva.penghapusan', 'sgu']) ? "Setujui " . $title . "<br>" . $record->code . "?": ""}}"
			@elseif(in_array($module,["ump.pengajuan-ump"]))
			data-swal-title="Setujui Pembayaran UMP @if($record->aktiva) {{ $record->aktiva->code }}@else {{ $record->pengajuanSgu->code }}@endif?"
			@elseif(in_array($module,["termin"]))
			data-swal-title="Setujui Pembayaran Termin @if($record->aktiva) {{ $record->aktiva->code }}@else {{ $record->pengajuanSgu->code }}@endif?"
			@endif
			data-url="{{ route($routes.'.pay', $record) }}">
			<i class="mr-3 fa fa-check text-primary"></i> {{__("Setujui")}}
		</button>
		<button type="button" class="dropdown-item"
			data-toggle="modal"
			data-target="#modalReject">
			<i class="mr-4 fa fa-times text-danger"></i> {{ __('Batal') }}
		</button>
		<button type="button" class="dropdown-item"
			data-toggle="modal"
			data-target="#modalRevision">
			<i class="mr-4 fa fa-edit text-success"></i> {{ __('Revisi') }}
		</button>
	</div>
</div>
