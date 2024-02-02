/**
 * ============================= BASE MODAL ============================
 * return render(options), loader(modal_id, loading=true)
 */
const BaseModal = function () {
	return {
		render: function (element, options = {}) {
			var el = $(element);
			var defaultOptions = {
					modal_id      : el.data('modal-id') ?? '#modal',
					modal_position: el.data('modal-position') ?? 'modal-dialog-centered modal-dialog-right-bottom', 
					modal_size    : el.data('modal-size') ? el.data('modal-size') : 'modal-lg',
					modal_bg      : el.data('modal-bg') ? el.data('modal-bg') : '',
					modal_timer   : el.data('modal-timer') ? el.data('modal-timer') : 500,
					modal_parent  : el.data('modal-parent') ? el.data('modal-parent') : '#content',
					modal_backdrop: el.data('modal-backdrop') ? el.data('modal-backdrop') : 'static',
					modal_keyboard: el.data('modal-keyboard') ? el.data('modal-keyboard') : false,
					modal_ajax    : el.data('modal-ajax') == false ? false : true,
					modal_url     : el.attr('href') ? el.attr('href') : (el.data('modal-url') ? el.data('modal-url') : '' ),
					callback      : false
				},
				options = $.extend(defaultOptions, options);
			
			$(options.modal_id).remove();
			if ($(options.modal_parent).length == 0) {
				options.modal_parent = 'body';
			}
			$(options.modal_parent).append(`
				<div class="modal fade modal-loading content-page" 
					id="`+(options.modal_id.replace('#', ''))+`" 
					data-keyboard="`+options.modal_keyboard+`" 
					data-backdrop="`+options.modal_backdrop+`" >
					<div class="modal-dialog `+options.modal_size+` `+options.modal_position+`" data-module-name="`+ModuleApp.getName()+`">
						<div class="modal-content `+options.modal_bg+`"></div>
					</div>
				</div>
			`);
			
			var modalLoadingTimer = setTimeout(function () {
				$(options.modal_id).modal('show');
				BaseModal.loader(options.modal_id, true);
			}, options.modal_timer);

			if (options.modal_ajax !== false && options.modal_url !== '') {
				BaseModal.handleByAjax(options, modalLoadingTimer);
			}
			else {
				if ($.isFunction(options.callback)) {
					options.callback(options, modalLoadingTimer);
				}
			}

		},
		loader: function (modal_id = '#modal', loading=true) {
			if ($(modal_id).length) {
				if ($(modal_id+' .modal-loader').length == 0) {
					$(modal_id+' .modal-content').append(`
						<div class="modal-loader pt-6">
							<span class="spinner spinner-primary"></span>
						</div>
					`);
				}
				if (loading) {
					$(modal_id+' .modal-loader').show();
				} else {
					$(modal_id+' .modal-loader').hide();
				}
			}
		},
		handleByAjax: function (options, modalLoadingTimer) {
			$.ajax({
				url: options.modal_url,
				dataType: 'html',
				success: function (resp) {
					clearTimeout(modalLoadingTimer);
					if (resp) {
						$(options.modal_id+' .modal-content').html(resp);
					}
					if (!$(options.modal_id).hasClass('show')) {
						$(options.modal_id).modal('show');
						BaseModal.loader(options.modal_id, false);
					}
					BasePlugin.init();
					if ($.isFunction(options.callback)) {
						options.callback(options);
					}
				},
				error: function (resp) {
					clearTimeout(modalLoadingTimer);
					$(options.modal_id+' .modal-content').html(`
						<div class="modal-header border-0">
							<h4 class="modal-title">Error!</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
						</div>
						<div class="modal-body py-5">
							<div class="alert alert-danger">
								Terjadi kesalahan!
							</div>	
						</div>
					`);
					if (!$(options.modal_id).hasClass('show')) {
						$(options.modal_id).modal('show');
						BaseModal.loader(options.modal_id, false);
					}
				},
			});
		},
		confirm: function (element, options = {}) {
			var el = $(element);
			var defaultOptions = {
					confirm_text  : el.data('confirm-text') ? el.data('confirm-text') : 'Are you sure?',
					url           : el.attr('href') ? el.attr('href') : (el.data('modal-url') ? el.data('modal-url') : ''),
					method        : el.attr('method') ? el.attr('method') : (el.data('method') ? el.data('method') : 'DELETE'),
					submit_class  : el.data('submit-class') ? el.data('submit-class') : 'base-form--submit-modal',
					modal_ajax    : false,
					modal_id      : '#modal_confirm',
					modal_size    : 'modal-confirm',
					modal_position: 'modal-dialog-centered modal-dialog-right-top',
					callback: function (options, modalLoadingTimer) {
						clearTimeout(modalLoadingTimer);
						$('#gritter-notice-wrapper').hide();
						$(options.modal_id+' .modal-content').html(`
							<div class="modal-body py-5 pl-5">
								<form action="`+options.url+`" method="POST">
									<input type="hidden" name="_token" value="`+BaseUtil.getToken()+`">	
									<input type="hidden" name="_method" value="`+options.method+`">	
									<table class="width-full">
										<tbody>
											<tr>
												<td class="valign-middle text-bold pr-5">`+options.confirm_text+`</td>
												<td class="valign-middle text-right width-150px">
													<button type="submit" class="btn btn-icon btn-circle btn-secondary `+options.submit_class+`" data-url="`+options.url+`"><i class="fa fa-check p-0"></i></button>
													<button type="button" class="btn btn-icon btn-circle btn-danger" data-dismiss="modal" aria-hidden="true"><i class="ki ki-close p-0"></i></button>
												</td>
											</tr>
										</tbody>
									</table>
								</form>
							</div>
						`);
						if (!$(options.modal_id).hasClass('show')) {
							$(options.modal_id).modal('show');
							BaseModal.loader(options.modal_id, false);
						}
					}
				},
				options = $.extend(defaultOptions, options);

			BaseModal.render(element, options);
		},
		documentEvent: function () {
			$(document).on('click', '.base-modal--render', function (e) {
				e.preventDefault();
				BaseModal.render(this);
			});			
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseModal;
}