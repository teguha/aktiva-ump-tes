<div class="modal fade modal-loading" id="modalRevision"
	data-keyboard="false"
	data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-md modal-dialog-centered">
		<div class="modal-content">
			<form action="{{ route($routes.'.reject', $record->id) }}" method="POST" autocomplete="off">
				@csrf
				@method('POST')
				<input type="hidden"
                       name="action"
                       value="revision">
				<div class="modal-header">
					<h4 class="modal-title">{{__('Revisi')}}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close"></i>
					</button>
				</div>
				<div class="modal-body text-left">
					<div class="form-group">
						<label>{{ __('Catatan') }}</label>
						<div class="parent-group">
							<textarea name="note" class="form-control" placeholder="{{ __('Catatan') }}"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success base-form--submit-modal"
						data-swal-confirm="true"
						@if(in_array($module,["pengajuan_pembelian.pengajuan", "pelepasan-aktiva.penghapusan", "sgu","mutasi-aktiva.pengajuan"]))
						data-swal-title = "{{in_array($module, ['pengajuan_pembelian.pengajuan', 'pelepasan-aktiva.penghapusan', 'sgu']) ? "Revisi " . $title . "<br>" . $record->code . "?": ""}}"
						@elseif(in_array($module,["ump.pengajuan-ump"]))
						data-swal-title="Revisi Pengajuan UMP?"
						@endif
						data-swal-text="{{ __('base.confirm.save.text') }}"
						data-swal-ok="{{ __('base.confirm.revision.ok') }}"
						data-swal-cancel="{{ __('base.confirm.revision.cancel') }}">
						<i class="fa fa-edit mr-1"></i>
						{{ __('Revisi') }}
					</button>
				</div>
			</form>
			<div class="modal-loader pt-6" style="display: none;">
				<span class="spinner spinner-primary"></span>
			</div>
		</div>
	</div>
</div>
