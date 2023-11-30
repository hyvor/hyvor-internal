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
        'provider' => env('LOGIN_PROVIDER', 'hyvor'),

        /**
         * Hyvor Login settings
         */
        'hyvor' => [
            /**
             * HYVOR Public URL
             * Users are redirected to this URL to login/signup
             */
            'url' => env('LOGIN_HYVOR_URL', 'https://hyvor.com'),

            /**
             * HYVOR Private URL (for internal API calls)
             * This is only required if you have HYVOR running on a private network
             * ex: http://0.0.0.1
             */
            'private_url' => env(
                'LOGIN_HYVOR_PRIVATE_URL',
                env('LOGIN_HYVOR_URL', 'https://hyvor.com')
            ),

            /**
             * HYVOR API Key
             */
            'api_key' => env('LOGIN_HYVOR_API_KEY', 'test-key'),
        ],

        /**
         * OpenID Connect settings
         */
        'oidc' => [
            /**
             * OpenID Connect Provider URL
             */
            'provider_url' => env('LOGIN_OIDC_PROVIDER_URL'),

            /**
             * Client ID
             */
            'client_id' => env('LOGIN_OIDC_CLIENT_ID'),

            /**
             * Client Secret
             */
            'client_secret' => env('LOGIN_OIDC_CLIENT_SECRET'),
        ],

        'fake' => [
            /**
             * Fake user ID
             */
            'user_id' => env('LOGIN_FAKE_USER_ID', 1),
        ]

    ]

];