<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../MODELO/Usuario.php';

class AuthController {
    
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Registrar nuevo usuario
     */
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtener datos del formulario
            $nombre = sanitize($_POST['nombre']);
            $apellidos = sanitize($_POST['apellidos']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $universidad = sanitize($_POST['universidad']);
            $carrera = sanitize($_POST['carrera']);
            $ciclo = sanitize($_POST['ciclo']);
            $telefono = sanitize($_POST['telefono']);
            
            // Validaciones
            if (empty($nombre) || empty($apellidos) || empty($email) || empty($password)) {
                setFlash('error', 'Todos los campos son obligatorios');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
                return;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setFlash('error', 'Email inválido');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
                return;
            }
            
            if (strlen($password) < 6) {
                setFlash('error', 'La contraseña debe tener al menos 6 caracteres');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
                return;
            }
            
            if ($password !== $password_confirm) {
                setFlash('error', 'Las contraseñas no coinciden');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
                return;
            }
            
            // Verificar si el email ya existe
            if ($this->usuarioModel->emailExiste($email)) {
                setFlash('error', 'El email ya está registrado');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
                return;
            }
            
            // Registrar usuario
            if ($this->usuarioModel->registrar($nombre, $apellidos, $email, $password, $universidad, $carrera, $ciclo, $telefono)) {
                setFlash('success', 'Registro exitoso. Ahora puedes iniciar sesión');
                redirect('MVC/VISTA/PRINCIPAL/login.php');
            } else {
                setFlash('error', 'Error al registrar usuario. Intenta nuevamente');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
            }
        }
    }
    
    /**
     * Login de usuario
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            
            // Validaciones
            if (empty($email) || empty($password)) {
                setFlash('error', 'Complete todos los campos');
                redirect('MVC/VISTA/PRINCIPAL/login.php');
                return;
            }
            
            // Intentar login
            if ($this->usuarioModel->login($email, $password)) {
                setFlash('success', '¡Bienvenido ' . $_SESSION['nombre'] . '!');
                redirect('index.php');
            } else {
                setFlash('error', 'Email o contraseña incorrectos');
                redirect('MVC/VISTA/PRINCIPAL/login.php');
            }
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        redirect('index.php');
    }
}

// Procesar acciones
if (isset($_GET['action'])) {
    $controller = new AuthController();
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        redirect('index.php');
    }
}
?>