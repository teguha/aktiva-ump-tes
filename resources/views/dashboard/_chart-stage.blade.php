<div class="col-md-12">
	<div class="card card-custom card-stretch gutter-b chart-stage-wrapper">
		<div class="card-header h-auto py-3">
			<div class="card-title">
				<h3 class="card-label">
					<span class="d-block text-dark font-weight-bolder">{{ __('Tahap Audit') }}</span>
				</h3>
			</div>
			<div class="card-toolbar" style="max-width: 500px;">
				<form id="filter-chart-stage" 
					action="{{ route($routes.'.chartStage') }}" 
					class="form-inline" 
					role="form">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="input-daterange input-group">
								<div class="input-group-append" data-toggle="tooltip" title="Filter">
									<span class="input-group-text">
										<i class="fa fa-filter"></i>
									</span>
								</div>
								<input type="text" 
									class="form-control stage_year" 
									name="stage_year" 
									value="{{ request()->stage_year ?? date('Y') }}">
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<select name="stage_object" 
								class="form-control base-plugin--select2-ajax stage_object"
								data-url="{{ route('ajax.selectObject', [
									'category' => 'all',
									'optstart_id' => 'all',
									'optstart_text' => __('Semua Objek'),
								]) }}"
								data-placeholder="{{ __('Semua Objek') }}">
								<option value="">{{ __('Semua Objek') }}</option>
							</select>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="card-body">
			<div class="chart-wrapper">
				<div id="chart-stage">
					<div class="d-flex h-100">
						<div class="spinners m-auto my-auto">
							<div class="spinner-grow text-success" role="status">
								<span class="sr-only">Loading...</span>
							</div>
							<div class="spinner-grow text-danger" role="status">
								<span class="sr-only">Loading...</span>
							</div>
							<div class="spinner-grow text-warning" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@push('styles')
	<style>
		.chart-stage-wrapper .apexcharts-menu-item.exportSVG,
		.chart-stage-wrapper .apexcharts-menu-item.exportCSV {
			display: none;
		}

		.chart-stage-wrapper .apexcharts-title-text {
			white-space: normal;
		}
	</style>
@endpush

@push('scripts')
	<script>
		$(function() {
		    iniFilterChartStage();
		    drawChartStage();
		});

		var iniFilterChartStage = function () {
		    $('input.stage_year').datepicker({
	            format: "yyyy",
			    viewMode: "years",
			    minViewMode: "years",
		        orientation: "bottom auto",
		        autoclose:true,
	        })
	        .on('changeDate', function (selected) {
	        	drawChartStage();
			});

		    $('.content-page').on('change', 'select.stage_object', function () {
	        	drawChartStage();
			});
		}

		var drawChartStage = function () {
			var filter = $('#filter-chart-stage');
			
			$.ajax({
				url: filter.attr('action'),
				method: 'POST',
				data: {
					_token: BaseUtil.getToken(),
					stage_year: filter.find('.stage_year').val(),
					stage_object: filter.find('.stage_object').val(),
				},
				success: function (resp) {
					$('.chart-stage-wrapper .chart-wrapper').find('#chart-stage').remove();
					$('.chart-stage-wrapper .chart-wrapper').html(`<div id="chart-stage"></div>`);
					renderChartStage(resp);
				},
				error: function (resp) {
					console.log(resp)
				}
			});
		}

		var renderChartStage = function (options = {}) {
			var element = document.getElementById('chart-stage');

            var defaultsOptions = {
            	title: {
            		text: options.title.text ?? 'Tahap Audit',
            		align: 'center',
            		style: {
    					fontSize:  '18px',
    					fontWeight:  '500',
    				},
            	},
                series: options.series ?? [],
                chart: {
                    type: 'line',
                    height: '400px',
                    stacked: true,
                    toolbar: {
                        show: true,
                        tools: {
    						download: true,
    						selection: false,
    						zoom: false,
    						zoomin: false,
    						zoomout: false,
    						pan: false,
    						reset: false,
    						customIcons: []
    					},
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['30%'],
                        endingShape: 'rounded'
                    },
                },
    	        legend: {
    	        	position: 'top',
    	        	offsetY: 2
    	        },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: [4, 0, 0, 0],
                    curve: 'smooth'
                    // colors: ['transparent']
                },
                xaxis: {
                    categories: options.xaxis.categories ?? [],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        }
                    }
                },
                fill: {
                    opacity: [0.3, 1, 1, 1],
    				gradient: {
    					inverseColors: false,
    					shade: 'light',
    					type: "vertical",
    				}
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    },
                    y: {
                        formatter: function(val) {
                            return val + " Objek"
                        }
                    }
                },
                colors: [
                	KTApp.getSettings()['colors']['theme']['base']['secondary'], 
                	KTApp.getSettings()['colors']['theme']['base']['info'],
                	KTApp.getSettings()['colors']['theme']['base']['success'], 
                ],
                grid: {
                    borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                noData: {
                	text: 'Loading...'
                }
            };

	        var chart = new ApexCharts(element, defaultsOptions);
	        chart.render();
		}
	</script>
@endpush