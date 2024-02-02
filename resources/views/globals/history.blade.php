@extends('layouts.modal')

@section('modal-title', __('Riwayat Aktivitas'))

@section('modal-body')
    <div class="mb-5">
        <div class="timeline timeline-justified timeline-4" style="max-height: 500px; overflow-y: auto;">
            <div class="timeline-bar"></div>
            <div class="timeline-items">
                @forelse ($record->logs()->whereModule($module)->latest()->latest('id')->get() as $item)
                    <div class="timeline-item">
                        <div class="timeline-badge">
                            <i class="fa fa-genderless text-info icon-md"></i>
                        </div>
                        <div class="timeline-label px-0">
                            <span class="text-primary font-weight-bolder">{{ $item->user->name ?? $item->creatorName() }}</span>
                            <span class="font-grey-cascade">{{ $item->creationDate() }}</span>
                        </div>

                        <div class="timeline-content">
                            <div class="font-grey-cascade">{!! $item->show_message !!}</div>
                        </div>
                    </div>
                @empty
                    <div class="timeline-item">
                        <div class="timeline-badge">
                            <i class="fa fa-genderless text-info icon-md"></i>
                        </div>
                        <div class="timeline-label px-0">
                            <span class="text-transparent font-weight-bolder">#</span>
                            <span class="text-transparent"></span>
                        </div>
                        <div class="timeline-content">
                            <div class="font-grey-cascade">{{ __('Data tidak tersedia!') }}</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('buttons')
@endsection

@push('scripts')
	<script>
		$('.modal-dialog').removeClass('modal-lg').addClass('modal-md');
		$('.modal-dialog').addClass('modal-dialog-right-bottom');

	</script>
@endpush