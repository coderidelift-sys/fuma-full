<?php

$menuItems = [
    [
        'items' => [
            [
                'title' => 'Dashboard',
                'icon' => 'ri-home-smile-line',
                'route' => 'dashboard',
                'active' => 'dashboard',
                'submenu' => [],
            ],
            [
                'title' => 'Analytics',
                'icon' => 'ri-bar-chart-2-line',
                'route' => 'dashboard',
                'active' => 'dashboard',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'User Managements',
        'items' => [
            [
                'title' => 'Users',
                'icon' => 'ri-user-line',
                'route' => 'users.index',
                'active' => 'users.*',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'Management',
        'items' => [
            [
                'title' => 'Tournaments',
                'icon' => 'ri-trophy-line',
                'route' => 'console.manage.tournaments.index',
                'active' => 'console.manage.tournaments.*',
                'submenu' => [],
            ],
            [
                'title' => 'Teams',
                'icon' => 'ri-team-line',
                'route' => 'console.manage.teams.index',
                'active' => 'console.manage.teams.*',
                'submenu' => [],
            ],
            [
                'title' => 'Players',
                'icon' => 'ri-user-voice-line',
                'route' => 'console.manage.players.index',
                'active' => 'console.manage.players.*',
                'submenu' => [],
            ],
            [
                'title' => 'Matches',
                'icon' => 'ri-football-line',
                'route' => 'console.manage.matches.index',
                'active' => 'console.manage.matches.*',
                'submenu' => [],
            ],
        ],
    ],
    [
        'header' => 'Settings',
        'items' => [
            [
                'title' => 'Profile',
                'icon' => 'ri-settings-4-line',
                'route' => 'profile.edit',
                'active' => 'profile.*',
                'submenu' => [],
            ],
        ],
    ],
];

return $menuItems;
