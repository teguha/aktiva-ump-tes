/**
 * ============================= BASE PLUGINS ============================
 * return render(options), loader(modal_id, loading=true)
 */
const BasePlugin = function () {
	"use strict";
	return {
		init: function () {
			this.initTooltipPopover();
			this.initSelect2();
			this.initSelectpicker();
			this.initDatepicker();
			this.initTimepicker();
			this.initSummernote();
			this.initInputMask();
			this.initTouchSpin();
		},
		initTooltipPopover: function () {
			$('.tooltip').remove();
			if ($('[data-toggle="tooltip"]').length) {
				$.each($('[data-toggle="tooltip"]'), function (i, el) {
					$(el).tooltip();
				});
			}
			if ($('[data-toggle="popover"]').length) {
				$.each($('[data-toggle="popover"]'), function (i, el) {
					$(el).popover();
				});
			}
		},
		initSelect2: function () {
			$('select.select2-hidden-accessible').select2('close');
			if ($('.base-plugin--select2').length) {
				$.each($('.base-plugin--select2'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							placeholder: me.attr('placeholder') ?? me.attr('data-placeholder'),
							dropdownParent: me.closest('form').length ? me.closest('form') : me.closest('body'),
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.select2(options);
				});
			}
			if ($('.base-plugin--select2-ajax').length) {
				$.each($('.base-plugin--select2-ajax'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							url     : me.data('url'),
							method: me.data('method') ?? 'POST',
							delay   : me.data('delay') ?? 500,
							cache   : me.data('cache') ?? true,
							minimumInputLength: me.data('min-input-length'),
							placeholder: me.attr('placeholder') ?? me.attr('data-placeholder'),
							dropdownParent: me.closest('form').length ? me.closest('form') : me.closest('body'),
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);
					if (options.url) {
						me.select2({
							ajax: {
								url: options.url,
								type: options.method,
								dataType: 'json',
								delay: options.delay,
								cache: options.cache,
								data: function (params) {
									return {
										_token: BaseUtil.getToken(),
										keyword: params.term, // search term
										page: params.page
									};
								},
								processResults: function (resp, params) {
									params.page = params.page || 1;

									return {
										results: resp.results ?? [],
										pagination: {
											more: resp.more ?? false
										}
									};
								},
							},
							minimumInputLength: options.minimumInputLength,
							placeholder: options.placeholder,
							dropdownParent: options.dropdownParent,
						});
					}
					else {
						me.select2({
							placeholder: options.placeholder,
							dropdownParent: options.dropdownParent,
						});
					}
				});
			}
		},
		initSelectpicker: function () {
			if ($('select.base-plugin--select, select.base-plugin--selectpicker').length) {
				alert("Please use class base-plugin--select2");
			}
		},
		initDatepicker: function () {
			$.fn.datepicker.defaults.format = "dd/mm/yyyy";
			$.fn.datepicker.defaults.language = $('html').attr('lang') ?? "en";
			if ($('.base-plugin--datepicker, .base-plugin--datepicker-1').length) {
				$.each($('.base-plugin--datepicker, .base-plugin--datepicker-1'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							autoclose     : me.data('auto-close') ?? true,
							todayHighlight: me.data('today-highlight') ?? true,
							orientation   : me.data('orientation') ?? 'auto',
							format: me.data('format') ?? 'dd/mm/yyyy',
							startView: me.data('start-view') ?? "days", 
							minViewMode: me.data('min-view') ?? "days"
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.datepicker(options);
				});
			}
			if ($('.base-plugin--datepicker-2').length) {
				$.each($('.base-plugin--datepicker-2'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							autoclose     : me.data('auto-close') ?? true,
							todayHighlight: me.data('today-highlight') ?? true,
							orientation   : me.data('orientation') ?? 'auto',
							format: me.data('format') ?? 'mm/yyyy',
							startView: me.data('start-view') ?? "months", 
							minViewMode: me.data('min-view') ?? "months"
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.datepicker(options);
				});
			}
			if ($('.base-plugin--datepicker-3').length) {
				$.each($('.base-plugin--datepicker-3'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							autoclose     : me.data('auto-close') ?? true,
							todayHighlight: me.data('today-highlight') ?? true,
							orientation   : me.data('orientation') ?? 'auto',
							format: me.data('format') ?? 'yyyy',
							startView: me.data('start-view') ?? "years", 
							minViewMode: me.data('min-view') ?? "years"
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.datepicker(options);
				});
			}
		},
		initTimepicker: function () {
			if ($('.base-plugin--timepicker').length) {
				$.each($('.base-plugin--timepicker'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							minuteStep  : me.data('minute-step') ?? 2,
							defaultTime : me.data('default-time') ?? '00:00',
							showSeconds : me.data('show-seconds') ?? false,
							showMeridian: me.data('show-meridian') ?? false,
							snapToStep  : me.data('snap-to-step') ?? true,
							orientation : me.data('orientation') ?? 'auto'
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);

					me.timepicker(options);
				});
			}
		},
		initSummernote: function () {
			if ($('.base-plugin--summernote-readonly').length) {
				$('.base-plugin--summernote-readonly').append(`
					<div class="note-statusbar" role="status">
						<div class="note-resizebar" aria-label="Resize">
							<div class="note-icon-bar"></div>
							<div class="note-icon-bar"></div>
							<div class="note-icon-bar"></div>
						</div>
					</div>`);
			}
			if ($('.base-plugin--summernote, .base-plugin--summernote-1').length) {
				$.each($('.base-plugin--summernote, .base-plugin--summernote-1'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							height: me.data('height') ?? 100,
							toolbar: me.data('toolbar') != undefined ? me.data('toolbar') : [
				                ['font', ['bold', 'italic', 'underline']],
				                ['para', ['ul', 'ol', 'paragraph']],
				                ['table', ['table']],
				                ['insert', ['link', 'picture']],
				            ],
				            callbacks: {
								// callback for pasting text only (no formatting)
								// onPaste: function (e) {
								// 	var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
								// 	e.preventDefault();
								// 	document.execCommand('insertHtml', false, bufferText);
								// }
							}
						},
						options = me.data('options') ?? {};

					if (options.toolbarMerge != undefined) {
						options.toolbar = defaultsOptions.toolbar.concat(options.toolbarMerge);
					}

					options = $.extend(defaultsOptions, options);
					me.summernote(options);

					if (me.prop('disabled')) {
						me.summernote('destroy');
						me.summernote({toolbar: false});
						me.parent().find('.note-editable').attr('contenteditable', false);
					}
				});
			}
			if ($('.base-plugin--summernote-2').length) {
				$.each($('.base-plugin--summernote-2'), function (i, el) {
					let me = $(el),
						defaultsOptions = {
							height  : me.data('height') ?? 300,
						},
						options = me.data('options') ?? {};

					options = $.extend(defaultsOptions, options);
					me.summernote(options);

					if (me.prop('disabled')) {
						me.summernote('destroy');
						me.summernote({toolbar: false});
						me.parent().find('.note-editable').attr('contenteditable', false);
					}
				});
			}
		},
		initInputMask: function () {
			if ($('.base-plugin--inputmask_currency').length) {
				$(".base-plugin--inputmask_currency").inputmask('currency', {
					alias: "numeric",
					prefix: "",
					groupSeparator: ",",
					radixPoint: ".",
					digits: 0,
					digitsOptional: !1,
					allowMinus: !1,
				});
			}

			if ($('.base-plugin--inputmask_int').length) {
				$(".base-plugin--inputmask_int").inputmask({
					"mask": "9",
					"repeat": 11,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_int_4').length) {
				$(".base-plugin--inputmask_int_4").inputmask({
					"mask": "9",
					"repeat": 4,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_int_2').length) {
				$(".base-plugin--inputmask_int_2").inputmask({
					"mask": "9",
					"repeat": 4,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_dec_2').length) {
				$(".base-plugin--inputmask_dec_2").inputmask('decimal', {
					"digits": 2,
					"rightAlign": false,
					"repeat": 4,
					"greedy": false
				});
			}
			if ($('.base-plugin--inputmask_phone').length) {
				$(".base-plugin--inputmask_phone").inputmask({
					"mask": "9",
					"repeat": 15,
					"greedy": false
				});
			}
		},
		initTouchSpin: function () {
			if ($('.base-plugin--touchspin_int').length) {
				$('.base-plugin--touchspin_int').TouchSpin({
					buttondown_class: 'btn btn-secondary',
					buttonup_class: 'btn btn-secondary',
					verticalbuttons: true,
					verticalup: '<i class="ki ki-plus"></i>',
					verticaldown: '<i class="ki ki-minus"></i>',

					min: 0,
					max: 9999999999,
					step: 1,
					decimals: 0,
					boostat: 5,
					maxboostedstep: 10,
				});
			}

			if ($('.base-plugin--touchspin_dec').length) {
				$('.base-plugin--touchspin_dec').TouchSpin({
					buttondown_class: 'btn btn-secondary',
					buttonup_class: 'btn btn-secondary',
					verticalbuttons: true,
					verticalup: '<i class="ki ki-plus"></i>',
					verticaldown: '<i class="ki ki-minus"></i>',

					min: 0,
					max: 9999999999,
					step: 0.01,
					decimals: 2,
					boostat: 5,
					maxboostedstep: 10,
				});
			}
		}
	}
}();

// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BasePlugin;
}