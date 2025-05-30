<?php

return [
    /**
     * If any input file(image) as default will use options below.
     */
    "image" => [
        /**
         * Path for store the image.
         *
         * Available options:
         * 1. public
         * 2. storage
         * 3. S3
         */
        "disk" => "storage",

        /**
         * Will be used if image is nullable and default value is null.
         */
        "default" => "https://via.placeholder.com/350?text=No+Image+Avaiable",

        /**
         * Crop the uploaded image using intervention image.
         */
        "crop" => true,

        /**
         * When set to true the uploaded image aspect ratio will still original.
         */
        "aspect_ratio" => true,

        /**
         * Crop image size.
         */
        "width" => 500,
        "height" => 500,
    ],

    "format" => [
        /**
         * Will be used to first year on select, if any column type year.
         */
        "first_year" => 1970,

        /**
         * If any date column type will cast and display used this format, but for input date still will used Y-m-d format.
         *
         * another most common format:
         * - M d Y
         * - d F Y
         * - Y m d
         */
        "date" => "Y-m-d",

        /**
         * If any input type month will cast and display used this format.
         */
        "month" => "Y/m",

        /**
         * If any input type time will cast and display used this format.
         */
        "time" => "H:i",

        /**
         * If any datetime column type or datetime-local on input, will cast and display used this format.
         */
        "datetime" => "Y-m-d H:i:s",

        /**
         * Limit string on index view for any column type text or long text.
         */
        "limit_text" => 100,
    ],

    /**
     * It will be used for generator to manage and showing menus on sidebar views.
     *
     * Example:
     * [
     *   'header' => 'Main',
     *
     *   // All permissions in menus[] and submenus[]
     *   'permissions' => ['test view'],
     *
     *   menus' => [
     *       [
     *          'title' => 'Main Data',
     *          'icon' => '<i class="bi bi-collection-fill"></i>',
     *          'route' => null,
     *
     *          // permission always null when isset submenus
     *          'permission' => null,
     *
     *          // All permissions on submenus[] and will empty[] when submenus equals to []
     *          'permissions' => ['test view'],
     *
     *          'submenus' => [
     *                 [
     *                     'title' => 'Tests',
     *                     'route' => '/tests',
     *                     'permission' => 'test view'
     *                  ]
     *               ],
     *           ],
     *       ],
     *  ],
     *
     * This code below always changes when you use a generator, and maybe you must format the code.
     */
    "sidebars" => [
    [
        'header' => 'Jurusan',
        'permissions' => [
            'jurusan view'
        ],
        'menus' => [
            [
                'title' => 'Jurusan',
                'icon' => '<i class="bi bi-diagram-3"></i>',
                'route' => '/jurusan',
                'permission' => 'jurusan view',
                'permissions' => [],
                'submenus' => []
            ]
        ]
    ],
    [
        'header' => 'Kelas',
        'permissions' => [
            'kelas view'
        ],
        'menus' => [
            [
                'title' => 'Kelas',
                'icon' => '<i class="bi bi-easel2"></i>',
                'route' => '/kelas',
                'permission' => 'kelas view',
                'permissions' => [],
                'submenus' => []
            ]
        ]
    ],
        [
        'header' => 'Ruang Ujian',
        'permissions' => [
            'ruang ujian view'
        ],
        'menus' => [
            [
                'title' => 'Ruang Ujian',
                'icon' => '<i class="bi bi-list"></i>',
                'route' => '/ruang-ujian',
                'permission' => 'ruang ujian view',
                'permissions' => [],
                'submenus' => []
            ]
        ]
            ],
    [
        'header' => 'Siswa',
        'permissions' => [
            'siswa view'
        ],
        'menus' => [
            [
                'title' => 'Siswa',
                'icon' => '<i class="bi bi-people"></i>',
                'route' => '/siswa',
                'permission' => 'siswa view',
                'permissions' => [],
                'submenus' => []
            ]
        ]
    ],
    [
        'header' => 'Utilities',
        'permissions' => [
            'user view',
            'role & permission view',
            'backup database view'
        ],
        'menus' => [
            [
                'title' => 'Utilities',
                'icon' => '<i class="bi bi-gear-fill"></i>',
                'route' => [
                    'users*',
                    'roles*',
                    'backup-database*'
                ],
                'permissions' => [
                    'user view',
                    'role & permission view',
                    'backup database view'
                ],
                'submenus' => [
                    [
                        'title' => 'User',
                        'route' => '/users',
                        'permission' => 'user view'
                    ],
                    [
                        'title' => 'Roles & permissions',
                        'route' => '/roles',
                        'permission' => 'role & permission view'
                    ],
                    [
                        'title' => 'Backup Database',
                        'route' => '/backup-database',
                        'permission' => 'backup database view',
                        'icon' => '<i class="bi bi-database"></i>'
                    ]
                ]
            ]
        ]
    ],
]
];
