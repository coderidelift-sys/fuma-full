<ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('fuma.dashboard') ? 'active' : '' }}">
        <a href="{{ route('fuma.dashboard') }}" class="menu-link">
            <i class="menu-icon ri-dashboard-line"></i>
            <div data-i18n="Dashboard">Dashboard</div>
        </a>
    </li>

    <!-- Divider -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Tournament Management</span>
    </li>

    <!-- Tournaments -->
    @if (in_array(auth()->user()->roles->first()->name ?? '', ['admin', 'organizer']))
    <li class="menu-item {{ request()->routeIs('fuma.tournaments.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.tournaments.index') }}" class="menu-link">
            <i class="menu-icon ri-trophy-line"></i>
            <div data-i18n="Tournaments">Tournaments</div>
        </a>
    </li>
    @endif

    <!-- Teams -->
    @if (in_array(auth()->user()->roles->first()->name ?? '', ['admin', 'manager']))
    <li class="menu-item {{ request()->routeIs('fuma.teams.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.teams.index') }}" class="menu-link">
            <i class="menu-icon ri-team-line"></i>
            <div data-i18n="Teams">Teams</div>
        </a>
    </li>
    @endif

    <!-- Players -->
    @if (in_array(auth()->user()->roles->first()->name ?? '', ['admin', 'manager', 'organizer']))
    <li class="menu-item {{ request()->routeIs('fuma.players.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.players.index') }}" class="menu-link">
            <i class="menu-icon ri-user-line"></i>
            <div data-i18n="Players">Players</div>
        </a>
    </li>
    @endif

    <!-- Matches -->
    @if (in_array(auth()->user()->roles->first()->name ?? '', ['admin', 'organizer', 'committee']))
    <li class="menu-item {{ request()->routeIs('fuma.matches.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.matches.index') }}" class="menu-link">
            <i class="menu-icon ri-football-line"></i>
            <div data-i18n="Matches">Matches</div>
        </a>
    </li>
    @endif

    <!-- Committees -->
    @if (in_array(auth()->user()->roles->first()->name ?? '', ['admin', 'organizer']))
    <li class="menu-item {{ request()->routeIs('fuma.committees.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.committees.index') }}" class="menu-link">
            <i class="menu-icon ri-user-settings-line"></i>
            <div data-i18n="Committees">Committees</div>
        </a>
    </li>
    @endif

    <!-- Divider -->
    @if (auth()->user()->roles->first()->name === 'admin')
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Administration</span>
    </li>

    <!-- Users Management -->
    <li class="menu-item {{ request()->routeIs('fuma.users.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.users.index') }}" class="menu-link">
            <i class="menu-icon ri-user-settings-line"></i>
            <div data-i18n="Users">Users</div>
        </a>
    </li>

    <!-- Roles Management -->
    <li class="menu-item {{ request()->routeIs('fuma.roles.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.roles.index') }}" class="menu-link">
            <i class="menu-icon ri-shield-user-line"></i>
            <div data-i18n="Roles">Roles</div>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Reports & Analytics</span>
    </li>

    <!-- Statistics -->
    <li class="menu-item {{ request()->routeIs('fuma.statistics.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.statistics.index') }}" class="menu-link">
            <i class="menu-icon ri-bar-chart-line"></i>
            <div data-i18n="Statistics">Statistics</div>
        </a>
    </li>

    <!-- Standings -->
    <li class="menu-item {{ request()->routeIs('fuma.standings.*') ? 'active' : '' }}">
        <a href="{{ route('fuma.standings.index') }}" class="menu-link">
            <i class="menu-icon ri-list-check-2"></i>
            <div data-i18n="Standings">Standings</div>
        </a>
    </li>

    <!-- Divider -->
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Settings</span>
    </li>

    <!-- Profile -->
    <li class="menu-item {{ request()->routeIs('fuma.profile') ? 'active' : '' }}">
        <a href="{{ route('fuma.profile') }}" class="menu-link">
            <i class="menu-icon ri-user-settings-line"></i>
            <div data-i18n="Profile">Profile</div>
        </a>
    </li>
</ul>
