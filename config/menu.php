<?php
return [
    'sidebar' => [
        "Hệ thống"           => [
            [
                'title'    => 'Người dùng',
                'href'     => 'admin/user',
                'icon'     => '<i class="fas fa-user"></i>',
                'is_admin' => true
            ],
            [
                'title'    => 'Cấu hình',
                'href'     => 'admin/config',
                'icon'     => '<i class="fas fa-wrench"></i>',
                'is_admin' => true
            ]
        ]
    ],
    'header'  => [
        [
            'title' => 'Dashboard',
            'href'  => 'admin/#',
        ], [
            'title' => 'Page',
            'href'  => 'admin/#',
        ], [
            'title' => 'Apps',
            'href'  => 'admin/#',
        ], [
            'title' => 'Layouts',
            'href'  => 'admin/#',
        ]
    ],
];
