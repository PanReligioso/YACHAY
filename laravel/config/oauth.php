<?php
return [ // retorna la configuracion de oauth
    'google' => [ // configuracion para google oauth
        'client_id' => env('GOOGLE_CLIENT_ID'), // id del cliente de google
        'client_secret' => env('GOOGLE_CLIENT_SECRET'), // secreto del cliente de google
        'redirect_uri' => env('GOOGLE_REDIRECT_URI', 'http://localhost/YACHAY/MVC/CONTROLADOR/GoogleAuthController.php'), // uri de redireccion usa un valor por defecto
    ],
];
