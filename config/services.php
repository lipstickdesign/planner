<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'model' => env('AI_MODEL', 'claude-sonnet-4-6'),
        // Sterkere modell for tung planlegging (AI-forslag treningstider)
        'model_pro' => env('AI_MODEL_PRO', 'claude-opus-5'),
    ],

    // NIF / Idrettsforbundet – Turneringsadmin (TA) kampdata. OAuth2 client_credentials.
    // Hemmeligheter settes i .env på serveren – aldri i kode.
    'nif' => [
        'client_id' => env('NIF_CLIENT_ID'),
        'client_secret' => env('NIF_CLIENT_SECRET'),
        'token_url' => env('NIF_TOKEN_URL', 'https://id.nif.no/connect/token'),
        'data_url' => env('NIF_DATA_URL', 'https://data.nif.no'),
        'scope' => env('NIF_SCOPE', 'data_ta_scheduledmatches_read'),
        'club_orgid' => env('NIF_CLUB_ORGID'),
        // Klubbens egen orgid slik den står i kampdataene (hometeamClubOrgId).
        // Oftest ulik club_orgid (som er spørre-id-en). Står den tom, autodetekteres den.
        'home_orgid' => env('NIF_HOME_ORGID'),
        'company_id' => env('NIF_COMPANY_ID'),
        'default_category' => env('NIF_DEFAULT_CATEGORY', 'Håndball'),
        // Idrett per admOrgId (særforbundets administrerende ledd) i TA-responsen.
        // 506503 = håndball (Region Sør), 387 = volleyball (Agder). Ukjente → default.
        'sport_map' => [
            '506503' => 'Håndball',
            '387' => 'Volleyball',
        ],
    ],

];
