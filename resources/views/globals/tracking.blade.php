@extends('layouts.modal')

@section('modal-title', __('Tracking Approval'))

@section('modal-body')
    <div class="timeline timeline-2">
        <div class="timeline-bar"></div>
        @forelse($record->approval($module)->get() as $val)
            <div class="timeline-item  d-flex align-items-start">
                <span class="timeline-badge bg-{{ $val->show_color }} mt-5px"></span>
                <div class="timeline-content d-flex align-items-start justify-content-between">
                    <span class="mr-3">
                        {{ $val->role->name }} <span class="text-{{ $val->show_color }}">({{ $val->show_type }})</span>
                        @if ($val->status == 'approved' && $val->user)
                            <div class="text-muted font-italic">
                                <div>{{ __('Approved by:') }} {{ $val->user->name }}</div>
                                <div>{{ __('Approved at:') }} {{ $val->approved_at->diffForHumans() }}</div>
                            </div>
                        @else
                            <div class="text-muted font-italic">
                                <div>{{ __('Created by:') }} {{ $val->creatorName() }}</div>
                                <div>{{ __('Created at:') }} {{ $val->creationDate() }}</div>
                            </div>
                        @endif
                    </span>
                    <span class="text-muted font-italic text-right">
                        {!! $val->labelStatus() !!}
                    </span>
                </div>
            </div>
        @empty
            <div class="alert alert-custom alert-light-danger mb-0 align-items-center">
                <div class="alert-text">{{ __('Data tidak tersedia!') }}</div>
            </div>
        @endforelse
    </div>
@endsection

@section('buttons')
@endsection

@push('scripts')
	<script>
			$('.modal-dialog').removeClass('modal-lg').addClass('modal-md');

	</script>
@endpush