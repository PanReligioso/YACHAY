<?php
// Configuración
define("BASE_URL", "http://localhost/YACHAY/");

// Iniciar sesión (Asegúrate de que la sesión esté siempre activa al usar helpers)
// OJO: Si usas session_start() en todos tus archivos, borra esta línea. Si no, déjala.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redireccionar
function redirect($url) {
    // CORRECCIÓN: Usar la concatenación de BASE_URL con la ruta relativa
    header("Location: " . BASE_URL . $url);
    exit;
}

// Limpiar datos
function sanitize($data) {
    // CORRECCIÓN: Agregar ENT_QUOTES para mejor seguridad XSS
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Verificar login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Obtener ID usuario
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Obtener nombre de usuario (Añadido para el header)
function getUserName(): string {
    // Asumimos que el nombre se guarda como 'username' en la sesión (que es el nombre_completo)
    if (isset($_SESSION['username'])) {
        return htmlspecialchars($_SESSION['username']);
    }
    return 'Usuario'; 
}

/**
 * Función AÑADIDA para verificar si el usuario es administrador.
 * Asume que el rol se guarda como 'role' en la sesión.
 * @return bool True si el rol es 'admin'.
 */
function isAdmin(): bool {
    // Es CRÍTICO que el valor 'admin' coincida con lo que guardas en la sesión
    return isLoggedIn() && (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}


// Mensajes flash
function setFlash($tipo, $mensaje) {
    // SINTAXIS CORREGIDA: Usar arrays anidados para soportar la función getFlash() de abajo
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>