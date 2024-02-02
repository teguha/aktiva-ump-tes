@extends('layouts.page')

@section('page')
    <div class="d-flex flex-row">
        @include($views.'.includes.profile-aside', ['tab' => 'notification'])

        <div class="flex-row-fluid ml-lg-8">
            <div class="card card-custom gutter-b">

                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">{{ __($title) }}</h3>
                        <span class="text-muted font-weight-bold font-size-sm mt-1">{{ __('Riwayat Notifikasi') }}</span>
                    </div>
                </div>

                <div class="card-body padding-20">
                	<table id="dataFilters" class="width-full">
                		<tbody>
                			<tr>
                				<td class="pb-2 valign-top td-filter-reset width-80px">
                					<div class="reset-filter mr-1 hide">
                						<button class="btn btn-secondary btn-icon width-full reset button" data-toggle="tooltip" data-original-title="Reset Filter"><i class="fas fa-sync"></i></button>
                					</div>
                					<div class="label-filter mr-1">
                						<button class="btn btn-secondary btn-icon width-full filter button" data-toggle="tooltip" data-original-title="Filter"><i class="fas fa-filter"></i></button>
                					</div>
                				</td>
                				<td>
                					<div class="row">
                						<div class="col-12 col-sm-6 col-xl-3 pb-2">
                							<select class="form-control base-plugin--select2-ajax filter-control"
                								data-post="module_name" 
                								data-placeholder="{{ __('Semua Modul') }}">
                								<option value="" selected>{{ __('Semua Modul') }}</option>
                								@foreach (\Base::getModules() as $key => $name)
                                                    <option value="{{ $key }}">{{ $name }}</option>
                                                @endforeach
                							</select>
                						</div>
                						<div class="col-12 col-sm-6 col-xl-3 pb-2">
                							<input type="text" class="form-control filter-control" data-post="message" placeholder="{{ __('Deskripsi') }}">
                						</div>
                					</div>
                				</td>
                				<td class="text-right td-btn-create"></td>
                			</tr>
                		</tbody>
                	</table>
                	<div class="table-responsive">
                	    @if(isset($tableStruct['datatable_1']))
                		    <table id="datatable_1" class="table table-bordered is-datatable" style="width: 100%;" data-url="{{ $tableStruct['url'] }}">
                		        <thead>
                		            <tr>
                		                @foreach ($tableStruct['datatable_1'] as $struct)
                		                	<th class="text-center v-middle"
                		                		data-columns-name="{{ $struct['name'] ?? '' }}"
                		                		data-columns-data="{{ $struct['data'] ?? '' }}"
                		                		data-columns-label="{{ $struct['label'] ?? '' }}"
                		                		data-columns-sortable="{{ $struct['sortable'] === true ? 'true' : 'false' }}"
                		                		data-columns-width="{{ $struct['width'] ?? '' }}"
                		                		data-columns-class-name="{{ $struct['className'] ?? '' }}"
                		                		style="{{ isset($struct['width']) ? 'width: '.$struct['width'].'; ' : '' }}">
                		                		{{ $struct['label'] }}
                		                	</th>
                		                @endforeach
                		            </tr>
                		        </thead>
                		        <tbody></tbody>
                		    </table>
                	    @endif
                	</div>
                </div>

            </div>
        </div>
        
    </div>
@endsection