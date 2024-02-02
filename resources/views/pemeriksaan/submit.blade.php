@extends('layouts.modal')

@section('modal-title', 'Flow Approval')

@section('action', route($routes . '.submitSave', $record->id))

@section('modal-body')
    @method('POST')
    <div class="timeline timeline-2">
        <div class="timeline-bar"></div>
        @forelse($flowApproval as $i => $flows)
            @foreach ($flows as $j => $flow)
                <div class="timeline-item">
                    <span class="timeline-badge bg-{{ $flow->show_color }}"></span>
                    <div class="timeline-content d-flex align-items-center justify-content-between">
                        <span class="mr-3">
                            {{ $flow->role->name }} <span
                                class="text-{{ $flow->show_color }}">({{ $flow->show_type }})</span>
                        </span>
                        <span class="text-muted font-italic text-right">{{ __('Approval Ke:') }} {{ $flow->order }}</span>
                    </div>
                </div>
            @endforeach
        @empty
            <div class="alert alert-custom alert-light-danger mb-0 align-items-center">
                <div class="alert-text">{{ __('Flow Approval tidak tersedia!') }}</div>
            </div>
        @endforelse
    </div>
@endsection

@section('buttons')
    @if ($flowApproval->count())
        <div class="modal-footer">
            @include('layouts.forms.btnSubmitModal')
        </div>
    @endif
@endsection
