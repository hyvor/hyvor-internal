<?php

return [

    'auth' => [

        /**
         * Login provider to use
         * 
         * - hyvor: Hyvor API (default, requires hyvor.com self-hosted)
         * - oidc: OpenID Connect (requires an OIDC provider)
         * - fake: Fake login (for testing)
         */
        'provider' => env('AUTH_PROVIDER', 'fake'),

        /**
         * Hyvor Login settings
         */
        'hyvor' => [
            /**
             * HYVOR Public URL
             * Users are redirected to this URL to login/signup
             */
            'url' => env('AUTH_HYVOR_URL', 'https://hyvor.com'),

            /**
             * HYVOR Private URL (for internal API calls)
             * This is only required if you have HYVOR running on a private network
             * ex: http://0.0.0.1
             */
            'private_url' => env(
                'AUTH_HYVOR_PRIVATE_URL',
                env('AUTH_HYVOR_URL', 'https://hyvor.com')
            ),

            /**
             * HYVOR API Key
             */
            'api_key' => env('AUTH_HYVOR_API_KEY', 'test-key'),
        ],

        /**
         * OpenID Connect settings
         */
        'oidc' => [
            /**
             * OpenID Connect Provider URL
             */
            'provider_url' => env('AUTH_OIDC_PROVIDER_URL'),

            /**
             * Client ID
             */
            'client_id' => env('AUTH_OIDC_CLIENT_ID'),

            /**
             * Client Secret
             */
            'client_secret' => env('AUTH_OIDC_CLIENT_SECRET'),
        ],

        'fake' => [
            /**
             * Fake user ID
             */
            'user_id' => env('AUTH_FAKE_USER_ID', 1),
        ]

    ],

    'i18n' => [

        /**
         * Folder that contains the locale JSON files
         */
        'folder' => base_path('locales'),

        /**
         * Default locale
         */
        'default' => 'en-US',

    ],

    'media' => [

        'path' => 'api/media',

        'disk' => 'public'

    ]

];