<?php
return [ // retorna la configuracion de oauth
    'google' => [ // configuracion para google oauth
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // Usar el valor de entorno o por defecto construir a partir de APP_URL
        'redirect_uri' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
    ],
];
