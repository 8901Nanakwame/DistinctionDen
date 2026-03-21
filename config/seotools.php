<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => env('APP_NAME', 'Distinction Den Online Tutorials'),
            'titleBefore'  => false,
            'description'  => 'Online learning platform offering exams, books, and educational resources for students.',
            'separator'    => ' - ',
            'keywords'     => ['online tutorials', 'exams', 'education', 'learning', 'books', 'study'],
            'canonical'    => 'current',
            'robots'       => 'index, follow',
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => env('APP_NAME', 'Distinction Den Online Tutorials'),
            'description' => 'Online learning platform offering exams, books, and educational resources for students.',
            'url'         => null,
            'type'        => 'website',
            'site_name'   => env('APP_NAME', 'Distinction Den Online Tutorials'),
            'images'      => [
                [
                    'url' => '/images/og-default.png',
                    'type' => 'image/png',
                ],
            ],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => null,
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => env('APP_NAME', 'Distinction Den Online Tutorials'),
            'description' => 'Online learning platform offering exams, books, and educational resources for students.',
            'url'         => null,
            'type'        => 'EducationalOrganization',
            'images'      => [
                '/images/og-default.png',
            ],
        ],
    ],
];
