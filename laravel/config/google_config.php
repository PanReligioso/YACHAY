<?php
// config/google_config.php

// Configuración de Google OAuth (Accediendo a las variables de entorno)
// Laravel automáticamente lee el .env al inicio.
define('GOOGLE_CLIENT_ID', env('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', env('GOOGLE_REDIRECT_URI'));
?>
