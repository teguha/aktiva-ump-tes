<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <div class="d-flex align-items-center flex-wrap mr-2">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">
                @section('content-title')
                    {{ $title }}
                @show
            </h5>
            
            @section('content-breadcrumb')
                @if (count($breadcrumb) >= 1)
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ url('/') }}" class="text-muted base-content--replace">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        
                        @foreach ($breadcrumb as $show => $link)
                            @if (!in_array(strtolower($show), ['home', 'dashboard']))
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ $link }}" class="text-muted base-content--replace">
                                        {{ __($show) }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            @show
        </div>

        <div class="d-flex align-items-center">
            @yield('buttons-right')
        </div>
    </div>
</div>
