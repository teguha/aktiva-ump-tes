/**
 * ============================= BASE LIST ============================
 * return render(options), loader(modal_id, loading=true)
 */
const BaseList = function () {
	"use strict";

	return {
		init: function (tables = ['#datatable_1','#datatable_2','#datatable_3'], options={}) {
			this.draw(tables, options);
		},
		draw: function (tables = ['#datatable_1','#datatable_2','#datatable_3'], options={}) {
			$.each(tables, function (i, table_id) {
				if($(table_id).length) {
					BaseList.render(table_id, options);
				}
			});
		},
		lang: function () {
			if ($('html').attr('lang') == 'id') {
				return {
					"sProcessing":  `<div class="spinners">
										<div class="spinner-grow text-success" role="status">
											<span class="sr-only">Loading...</span>
										</div>
										<div class="spinner-grow text-danger" role="status">
											<span class="sr-only">Loading...</span>
										</div>
										<div class="spinner-grow text-warning" role="status">
											<span class="sr-only">Loading...</span>
										</div>
									</div>`,
					"sLengthMenu":   "Menampilkan _MENU_ data per halaman",
					"sZeroRecords":  "Tidak ditemukan data yang sesuai",
					"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
					"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 data",
					"sInfoFiltered": "(difilter dari _MAX_ keseluruhan data)",
					"sInfoPostFix":  "",
					"sSearch":       "Cari:",
					"sUrl":          "",
					"oPaginate": {
						"sFirst":    "Pertama",
						"sPrevious": "Sebelumnya",
						"sNext":     "Selanjutnya",
						"sLast":     "Terakhir"
					}
				}
			}
			else {
				return {
					"sProcessing":  `<div class="spinners">
										<div class="spinner-grow text-success" role="status">
											<span class="sr-only">Loading...</span>
										</div>
										<div class="spinner-grow text-danger" role="status">
											<span class="sr-only">Loading...</span>
										</div>
										<div class="spinner-grow text-warning" role="status">
											<span class="sr-only">Loading...</span>
										</div>
									</div>`,
					"sLengthMenu":   "Display _MENU_ data per page",
					"sZeroRecords":  "Nothing found",
					"sInfo":         "Showing _START_ to _END_ of _TOTAL_ data",
					"sInfoEmpty":    "No data available",
					"sInfoFiltered": "(filtered from _MAX_ total data)",
					"sInfoPostFix":  "",
					"sSearch":       "Search:",
					"sUrl":          "",
					"oPaginate": {
						"sFirst":    "First",
						"sPrevious": "Previous",
						"sNext":     "Next",
						"sLast":     "End"
					}
				}
			}
		},
		render: function (table_id, options={}) {
		    var el = $(table_id);

		    var defaultOptions = {
					columns : [],
					url     : el.data('url'),
					method  : 'POST',
					token   : BaseUtil.getToken(),
					callback: false,
					tooltip: false,
			    },
			    options = $.extend(defaultOptions, options);

		    if (el.length) {
		        if ( !$.fn.DataTable.isDataTable( table_id ) ) {
		        	$.each(el.find('thead th'), function (i) {
		        		var th = $(this);
		        		options.columns[i] = {
			        		sName: th.data('columns-name'),
			        		mData: th.data('columns-data'),
			        		label: th.data('columns-label'),
			        		bSortable: th.data('columns-sortable'),
			        		sWidth: th.data('columns-width'),
			        		sClass: th.data('columns-class-name'),
			        	}
		        	});

		            el.DataTable({
		                lengthChange: false,
		                filter: false,
		                processing: true,
		                serverSide: true,
		                autoWidth: true,
		                sorting: [],
		                language: BaseList.lang(),
		                paging: (el.data('paging') == false ? false : true),
		                info: (el.data('info') == false ? false : true),
		                ajax: {
		                    url: options.url,
		                    method: options.method,
		            		// beforeSend: function () {
		            		// 	setTimeout(function () {
		            		// 		el.removeClass('hide');
		            		// 	}, 500);
		            		// },
		                    data: function (request) {
		                        request._token = options.token;
		                        $('#dataFilters input.filter-control, #dataFilters select.filter-control').each(function() {
		                            var name = $(this).data('post'),
		                                val = $(this).val();
		                            request[name] = val;
		                        });
		                        if (options.extraData !== undefined) {
		                        	request['extraData'] = options.extraData;
		                        }
		                    },
		                    error: function (responseError, status) {
		                    	if (el.hasClass('first-render-has-error')) {
			                        console.log(responseError);
			                        return false;
		                    	}
		                    	else {
		                    		el.addClass('first-render-has-error');
		                    		el.DataTable().draw();
		                    	}
		                    }
		                },
		                columns: options.columns,
		                drawCallback: function(resp) {
		                	el.removeClass('hide');

		                    this.api().column(0, {
		                    	search:'applied', 
		                    	order:'applied'
		                    }).nodes().each( function (cell, i, x, y) {
		                    	if (parseInt(cell.innerHTML)+i+1) {
		                        	cell.innerHTML = parseInt(cell.innerHTML)+i+1;
		                    	}
		                    });

	                    	if (el.data('badge') == true && el.data('tab-list') != undefined) {
	                    		var tab_list = $('.tab-list[href="'+el.data('tab-list')+'"]').first(),
	                                total_records = resp.json.recordsTotal;

	                    		if (total_records && tab_list.length) {
	                    		    tab_list.find('.tab-badge').remove();
	                    		    tab_list.append(`
	                    		    	<span class="label label-success tab-badge ml-2  label-inline">`+total_records+`</span>
	                    		    `);
	                    		}
	                    	}

	                    	el.find('td .btn, td .make-td-py-0').closest('td').addClass('py-0');
	                    	if ($.isFunction(options.callback)) {
	                    		options.callback(options, resp);
	                    	}
	                    	
	                    	BasePlugin.initTooltipPopover();
		                }
		            });

		            if (BaseUtil.isDev() === false) {
			            $.fn.dataTable.ext.errMode = 'none';
			            el.on( 'error.dt', function ( e, settings, techNote, message ) {
			            	console.log( 'An error has been reported by DataTables: ', message );
			            }).DataTable();
		            }
		        } 
		        else {
		            el.DataTable().draw();
		        }
		    }
		},
		handleDataFilters: function () {
	    	var isFiltered = false;
	    	$.each($('.filter-control'), function () {
	    		if ($(this).val()) {
	    			isFiltered = true;
	    			return false;
	    		}
	    	});

	    	if (isFiltered) {
	    		$('#dataFilters .label-filter').addClass('hide');
	    		$('#dataFilters .reset-filter').removeClass('hide');
	    	}
	    	else {
	    		$('#dataFilters .reset-filter').addClass('hide');
		    	$('#dataFilters .label-filter').removeClass('hide');
	    	}
		},
		documentEvent: function () {
			var filterTimer;
		    $(document).on('keyup', '#dataFilters input.filter-control', function(e) {
	    		clearTimeout(filterTimer);
	    		filterTimer = setTimeout(function() {
	    	    	BaseList.draw();
	    		}, 500);

	    		BaseList.handleDataFilters();
		    });

		    $(document).on('change', '#dataFilters select.filter-control, #dataFilters input.filter-control[class*=date]', function(e) {
			    BaseList.draw();
			    BaseList.handleDataFilters();
		    });

			$(document).on('click', '#dataFilters .filter.button', function(e) {
		        e.preventDefault();
		        BaseList.draw();
		    });

		    $(document).on('click', '#dataFilters .reset.button', function(e) {
		        $('#dataFilters .filter-control').val('');
			    $('#dataFilters .reset-filter').addClass('hide');
			    $('#dataFilters .label-filter').removeClass('hide');

		        BasePlugin.init();
		        BaseList.draw();
		    });

		    $(document).on('click', '.tab-list', function () {

		    	var me = $(this);
		    	var colors = ['primary', 'info', 'success', 'warning', 'danger'];
		    	$.each(colors, function (i, color) {
		    		if (me.hasClass('nav-link-'+color)) {
		    			me.closest('.nav-tabs').addClass('nav-tabs-'+color);
		    		}
		    		else {
		    			me.closest('.nav-tabs').removeClass('nav-tabs-'+color);
		    		}
		    	});
		    });

		    $(document).on('shown.bs.dropdown', 'table.is-datatable', function (e) {
		        // The .dropdown container
		        var me = $(e.target);

		        // Find the actual .dropdown-menu
		        var dropdown = me.find('.dropdown-menu');
		        if (dropdown.length) {
		            // Save a reference to it, so we can find it after we've attached it to the body
		            me.data('dropdown-menu', dropdown);
		        } else {
		            dropdown = me.data('dropdown-menu');
		        }

		        dropdown.css('top', (me.offset().top + me.outerHeight()) + 'px');
		        dropdown.css('left', me.offset().left + 'px');
		        dropdown.css('position', 'absolute');
		        dropdown.css('display', 'block');
		        dropdown.appendTo('#content-page');

		    });

		    $(document).on('hide.bs.dropdown', 'table.is-datatable', function (e) {
		        // Hide the dropdown menu bound to this button
		        $(e.target).data('dropdown-menu').css('display', 'none');
		    });
		}
	}
}();


// webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = BaseList;
}