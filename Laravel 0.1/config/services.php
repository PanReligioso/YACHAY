<?php

return [ // retorna un array de configuraciones de servicios externos

    'mailgun' => [ // configuracion del servicio de correo mailgun
        'domain' => env('MAILGUN_DOMAIN'), // dominio de mailgun de la variable de entorno
        'secret' => env('MAILGUN_SECRET'), // clave secreta de mailgun
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'), // endpoint de la api de mailgun
        'scheme' => 'https', // esquema de protocolo
    ],

    'postmark' => [ // configuracion del servicio de correo postmark
        'token' => env('POSTMARK_TOKEN'), // token de postmark de la variable de entorno
    ],

    'ses' => [ // configuracion del servicio Amazon SES
        'key' => env('AWS_ACCESS_KEY_ID'), // clave de acceso de AWS
        'secret' => env('AWS_SECRET_ACCESS_KEY'), // clave secreta de AWS
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'), // region por defecto de AWS
    ],

    'google' => [ // configuracion del servicio Google Socialite
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // fallback: usar APP_URL si GOOGLE_REDIRECT_URI no está definido
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
    ],
];
 