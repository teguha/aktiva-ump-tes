<div id="kt_aside" class="aside aside-left aside-fixed d-flex flex-column flex-row-auto page-with-light-sidebar">
    {{-- Brand --}}
    <div class="brand flex-column-auto pr-3" id="kt_brand">
        <div class="brand-logo m-auto">
            <a href="{{ url('/') }}">
                <img src="{{ asset(config('base.logo.aside')) }}" alt="Image" style="max-width: 170px; max-height: 40px;"/>
            </a>
        </div>

        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            {!! \Base::getSVG("assets/media/svg/icons/Navigation/Angle-double-left.svg", "svg-icon-xl") !!}
        </button>
    </div>

    {{-- Aside menu --}}
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <div
            id="kt_aside_menu"
            class="aside-menu my-4"
            resize="true"
            data-menu-vertical="1"
            data-menu-scroll="1" 
            data-menu-dropdown-timeout="250">

            @if (config('base.custom-menu'))
                {{-- Custom menu --}}
                <div class="custom-menu">
                    <ul class="menu-nav nav">
                       @foreach (config('backendmenu') as $menu)                        
                           @if (!empty($menu['perms']) && !auth()->user()->checkPerms($menu['perms'].'.view'))
                               @continue;
                           @endif
                           {!! \Base::renderMenuTree($menu) !!}
                       @endforeach
                    </ul>
                </div>
            @else
                {{-- Default metronic menu --}}
                <ul class="menu-nav">
                    @foreach (config('backendmenu') as $menu)
                        @if (isset($menu['permission']) && !auth()->user()->checkPerms($menu['permission']))
                            @continue;
                        @endif
                        {!! \Base::renderAsideMenu($menu) !!}
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>