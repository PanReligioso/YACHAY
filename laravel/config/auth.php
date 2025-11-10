<?php

return [
    'defaults' => [ // configuracion de autenticacion por defecto
        'guard' => 'web', // establece el guard por defecto para la web
        'passwords' => 'users', // establece la configuracion de reseteo de contraseñas por defecto
    ],

    'guards' => [ // definicion de los guards de autenticacion
        'web' => [ // guard web usa sesiones
            'driver' => 'session', // driver de sesion
            'provider' => 'users', // proveedor de usuarios
        ],
    ],

    'providers' => [ // definicion de los proveedores de usuarios
        'users' => [ // proveedor de usuarios llamado users
            'driver' => 'eloquent', // usa el driver eloquent para la base de datos
            'model' => App\Models\Usuario::class, // establece el modelo Usuario como la fuente de autenticacion
        ],
    ],

    'passwords' => [ // configuracion para el reseteo de contraseñas
        'users' => [ // configuracion para el proveedor users
            'provider' => 'users', // usa el proveedor de usuarios users
            'table' => 'password_reset_tokens', // tabla para almacenar los tokens
            'expire' => 60, // tiempo en minutos antes de que expire el token
            'throttle' => 60, // tiempo en segundos entre intentos de reseteo
        ],
    ],

    'password_timeout' => 10800, // tiempo maximo de inactividad antes de requerir reautenticacion
];
