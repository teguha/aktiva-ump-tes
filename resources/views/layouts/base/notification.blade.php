@if (isset($notifications) && count($notifications))
    <div class="navi navi-hover scroll my-4 user-notification-items"
        data-count="{{ auth()->user()->notifications()->wherePivot('readed_at', null)->count() }}"
        data-scroll="true" 
        style="min-height: 100px; max-height: 300px;">
        @foreach ($notifications as $item)
            <a href="{{ route('ajax.userNotificationRead', $item->id) }}" class="navi-item base-content--replace {{ $loop->last ? 'mb-7' : '' }}" >
                <div class="navi-link">
                    <div class="navi-icon mr-2 v-align-top">
                        <i class="flaticon2-notification {{ $item->pivot->readed_at ? '' : 'text-success' }}"></i>
                    </div>
                    <div class="navi-text">
                        <div class="text-bold text-app">{!! \Base::getModules($item->module) !!}</div>
                        <div class="text-normal">{!! $item->message !!}</div>
                        <div class="text-muted">Created By: {{ $item->from_name }}</div>
                        <div class="text-muted">Created At: {{ $item->creationDate(false) }}</div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="text-center">
        <div class="separator separator-solid  my-5"></div>
        <a href="{{ route('setting.profile.notification') }}" 
            class="btn btn-light-primary mb-3 base-content--replace">
            {{ 'Baca Semua' }} <i class="fas fa-angle-right ml-2"></i>
        </a>
    </div>
@else
    <div class="d-flex flex-center text-center text-muted min-h-200px">
        {{ __('Data tidak tersedia!') }}
    </div>
@endif