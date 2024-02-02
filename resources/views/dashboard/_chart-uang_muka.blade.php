<div class="col-md-6">
	<div class="card card-custom card-stretch gutter-b chart-pengaduan-wrapper">
		<div class="card-header h-auto py-3">
			<div class="card-title">
				<h3 class="card-label">
					<span class="d-block text-dark font-weight-bolder">{{ __('Uang Muka Pembayaran') }}</span>
				</h3>
			</div>
			<div class="card-toolbar" style="max-width: 500px;">
				
				<form id="filter-chart-pengaduan"
					action="{{ route($routes.'.chartUmp') }}"
					class="form-inline"
					role="form">
					<div class="row d-flex justify-content-end">
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
					</div>
				</form>
			</div>
		</div>
		<div class="card-body">
			<div class="chart-wrapper">
				<div id="chart-pengaduan">
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
		.chart-pengaduan-wrapper .apexcharts-menu-item.exportSVG,
		.chart-pengaduan-wrapper .apexcharts-menu-item.exportCSV {
			display: none;
		}

		.chart-pengaduan-wrapper .apexcharts-title-text {
			white-space: normal;
		}
	</style>
@endpush

@push('scripts')
	<script>
		$(function() {
		    iniFilterChartPengaduan();
		    drawChartPengaduan();
		});

		var iniFilterChartPengaduan = function () {
		    $('input.stage_year').datepicker({
	            format: "yyyy",
			    viewMode: "years",
			    minViewMode: "years",
		        orientation: "bottom auto",
		        autoclose:true,
	        })
	        .on('changeDate', function (selected) {
	        	drawChartPengaduan();
			});
		}

		var drawChartPengaduan = function () {
			var filter = $('#filter-chart-pengaduan');

			$.ajax({
				url: filter.attr('action'),
				method: 'POST',
				data: {
					_token: BaseUtil.getToken(),
					stage_year: filter.find('.stage_year').val(),
				},
				success: function (resp) {
					$('.chart-pengaduan-wrapper .chart-wrapper').find('#chart-pengaduan').remove();
					$('.chart-pengaduan-wrapper .chart-wrapper').html(`<div id="chart-pengaduan"></div>`);
					renderChartPengaduan(resp);
				},
				error: function (resp) {
					console.log(resp)
				}
			});
		}

		var renderChartPengaduan = function (options = {}) {
			var element = document.getElementById('chart-pengaduan');

	        var defaultsOptions = {
	        	title: {
	        		text: options.title.text ?? '',
	        		align: 'center',
	        		style: {
						fontSize:  '18px',
						fontWeight:  '500',
					},
	        	},
	            series: options.series ?? [],
	            chart: {
	                type: 'bar',
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
	                opacity: [1, 1, 1, 1, 1, 1],
					gradient: {
						inverseColors: false,
						shade: 'light',
						type: "vertical",
					}
	            },
	            tooltip: {
	                style: {
	                    fontSize: '12px',
	                    fontFamily: KTApp.getSettings()['font-family']
	                },
	                y: {
	                    formatter: function(val) {
	                        return val
	                    }
	                }
	            },
	            colors: options.colors ?? [
	            	KTApp.getSettings()['colors']['theme']['base']['secondary'],
	            	KTApp.getSettings()['colors']['theme']['base']['danger'],
	            	KTApp.getSettings()['colors']['theme']['light']['warning'],
	            	KTApp.getSettings()['colors']['theme']['base']['warning'],
	            	KTApp.getSettings()['colors']['theme']['light']['success'],
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
