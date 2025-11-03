<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../../config/google_config.php';
require_once __DIR__ . '/../MODELO/Usuario.php';

class GoogleAuthController {
    
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Redirigir a Google para autenticación
     */
    public function login() {
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];
        
        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header('Location: ' . $authUrl);
        exit;
    }
    
    /**
     * Callback de Google - procesar respuesta
     */
    public function callback() {
        if (!isset($_GET['code'])) {
            setFlash('error', 'Error al autenticar con Google');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        $code = $_GET['code'];
        
        // Intercambiar código por token
        $tokenData = $this->getAccessToken($code);
        
        if (!$tokenData || !isset($tokenData['access_token'])) {
            setFlash('error', 'Error al obtener token de Google');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        // Obtener información del usuario
        $userInfo = $this->getUserInfo($tokenData['access_token']);
        
        if (!$userInfo) {
            setFlash('error', 'Error al obtener información de usuario');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        // Registrar o iniciar sesión
        $this->loginOrRegister($userInfo);
    }
    
    /**
     * Obtener access token
     */
    private function getAccessToken($code) {
        $params = [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    /**
     * Obtener información del usuario
     */
    private function getUserInfo($accessToken) {
        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    /**
     * Login o registro automático
     */
    private function loginOrRegister($userInfo) {
        $email = $userInfo['email'];
        $nombre = $userInfo['given_name'] ?? '';
        $apellidos = $userInfo['family_name'] ?? '';
        $foto = $userInfo['picture'] ?? '';
        $googleId = $userInfo['id'];
        
        // Verificar si el usuario existe
        if ($this->usuarioModel->emailExiste($email)) {
            // Usuario existe - hacer login
            $usuario = $this->usuarioModel->obtenerPorEmail($email);
            
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['role'] = $usuario['role'];
            
            setFlash('success', '¡Bienvenido de nuevo ' . $usuario['nombre'] . '!');
            redirect('index.php');
        } else {
            // Usuario nuevo - registrar automáticamente
            // Generar password aleatorio (no se usará para login con Google)
            $randomPassword = bin2hex(random_bytes(16));
            
            // Registrar con datos de Google
            if ($this->usuarioModel->registrarConGoogle($nombre, $apellidos, $email, $randomPassword, $foto, $googleId)) {
                // Obtener el usuario recién creado
                $usuario = $this->usuarioModel->obtenerPorEmail($email);
                
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['username'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['role'] = $usuario['role'];
                
                setFlash('success', '¡Cuenta creada exitosamente! Completa tu perfil');
                redirect('MVC/VISTA/PRINCIPAL/perfil.php');
            } else {
                setFlash('error', 'Error al crear la cuenta');
                redirect('MVC/VISTA/PRINCIPAL/registro.php');
            }
        }
    }
}

// Procesar acciones
$controller = new GoogleAuthController();

if (isset($_GET['action']) && $_GET['action'] === 'login') {
    // Iniciar proceso de autenticación
    $controller->login();
} else {
    // Callback de Google
    $controller->callback();
}
?>