<?php

return [

    'interakt' => [
        'api_key' => env('INTERAKT_API_KEY'),
        'api_url' => env('INTERAKT_API_URL', 'https://api.interakt.ai/v1/public/message/'),
        'template_name' => env('INTERAKT_TEMPLATE_NAME', 'otp'),
        'language_code' => env('INTERAKT_LANGUAGE_CODE', 'en'),
    ],

];
