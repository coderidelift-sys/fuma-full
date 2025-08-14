<ul class="menu-inner py-1">
    @php
        $menuWhereRole = config('console-menu');
    @endphp

    @foreach ($menuWhereRole as $menu)
        @if (isset($menu['header']))
            <li class="menu-header">
                <span class="menu-header-text" data-i18n="{{ $menu['header'] }}">{{ $menu['header'] }}</span>
            </li>
        @endif
        @foreach ($menu['items'] as $item)
            <li class="menu-item {{ setSidebarActive($item['active']) }} {{ setSubSidebarActive($item['submenu']) }}">
                <a href="{{ $item['route'] != '' ? route($item['route']) : 'javascript:void(0);' }}"
                    class="menu-link {{ $item['submenu'] ? 'menu-toggle' : '' }}">
                    <i class="menu-icon tf-icons {{ $item['icon'] }}"></i>
                    <div data-i18n="{{ $item['title'] }}">{{ $item['title'] }}</div>
                </a>
                @if (!empty($item['submenu']))
                    <ul class="menu-sub">
                        @foreach ($item['submenu'] as $submenu)
                            <li class="menu-item {{ setSidebarActive($submenu['active']) }}">
                                <a href="{{ $submenu['route'] != '' ? route($submenu['route']) : 'javascript:void(0);' }}"
                                    class="menu-link">
                                    <div data-i18n="{{ $submenu['title'] }}">{{ $submenu['title'] }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    @endforeach
</ul>
