<?php
// config/google_config.php // archivo de configuracion custom para google

// Configuracion de Google OAuth (Accediendo a las variables de entorno)
// Laravel automaticamente lee el .env al inicio.
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID')); // define la constante con el id del cliente
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET')); // define la constante con el secreto del cliente
define('GOOGLE_REDIRECT_URI', env('GOOGLE_REDIRECT_URI')); // define la constante con la URI de redireccion
?>
