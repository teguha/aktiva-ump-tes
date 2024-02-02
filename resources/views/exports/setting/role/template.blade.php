@inject('permission', '\App\Models\Auth\Permission')
<table>
    <thead>
        <tr>
            <th rowspan="3" 
                style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
                {{ strtoupper('No') }}
            </th>
            <th rowspan="3" 
                style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
                {{ strtoupper('Role') }}
            </th>
            <th colspan="{{ (count($modules) * 5) }}" 
                style="text-align: left; vertical-align: center; font-weight: bold; background-color: #F64E60;">
                {{ strtoupper('Assign Permission (Masukkan Y untuk memberikan hak akses)') }}
            </th>
        </tr>
        <tr>
            @foreach ($modules as $i => $menu)
                <th colspan="5" 
                    style="text-align: center; vertical-align: center; font-weight: bold; background-color: {{ $colors[$i%5] }};">
                    {{ strtoupper($menu['title']) }}
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach ($modules as $menu)
                @foreach ($actions as $action)
                    @if ($permission->where('name', $menu['perms'].'.'.$action)->exists())
                        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #D1D3E0;">
                            {{ strtoupper($action) }}
                        </th>
                    @else
                        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #F64E60; color: #F64E60;">
                            {{ strtoupper($action) }}
                        </th>
                    @endif
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>