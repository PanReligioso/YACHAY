<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    
    private $db;
    
    public function __construct() {
        $this->db = Conectar::conexion();
    }
    
    /**
     * Función auxiliar para bind_param (ya que no la tenía en el código original)
     */
    private function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) { $refs = array(); foreach($arr as $key => $value) $refs[$key] = &$arr[$key]; return $refs; } return $arr;
    }

    /**
     * Registrar nuevo usuario
     */
    public function registrar($nombre, $apellidos, $email, $password, $universidad, $carrera, $ciclo, $telefono) {
        // CORRECCIÓN 1: La consulta usa 9 placeholders. id_rol está fijo en 3.
        $query = "INSERT INTO usuarios (nombre_completo, apellidos, email, password, universidad, carrera, ciclo, telefono, id_rol) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
        
        $stmt = $this->db->prepare($query);
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $nombreCompleto = trim($nombre . ' ' . $apellidos);
        $id_rol_default = 3; // El valor fijo se pasa como una variable
        
        // CORRECCIÓN 2: Se necesitan 9 's' para 9 parámetros (8 strings + 1 entero, usando 's' para todos por conveniencia)
        $stmt->bind_param("ssssssssi", 
            $nombreCompleto, 
            $apellidos, 
            $email, 
            $hashedPassword, 
            $universidad, 
            $carrera, 
            $ciclo, 
            $telefono,
            $id_rol_default // 9º parámetro
        );

        return $stmt->execute();
    }
    
    /**
     * Login de usuario
     */
    public function login($email, $password) {
        $query = "SELECT u.id_usuario, u.nombre_completo, u.email, u.password, r.nombre_rol AS role_name
                  FROM usuarios u
                  INNER JOIN roles r ON u.id_rol = r.id_rol
                  WHERE u.email = ? LIMIT 1"; 
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $user = $resultado->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['username'] = $user['nombre_completo']; 
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role_name'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Verificar si email existe
     */
    public function emailExiste($email) {
        $query = "SELECT id_usuario FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }
    
    /**
     * Obtener usuario por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT u.*, r.nombre_rol AS role 
                  FROM usuarios u
                  INNER JOIN roles r ON u.id_rol = r.id_rol
                  WHERE u.id_usuario = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $user = $resultado->fetch_assoc();
        
        if ($user) {
            $parts = explode(' ', $user['nombre_completo'], 2);
            $user['nombre'] = $parts[0] ?? '';
            $user['apellidos'] = $user['apellidos'] ?? $parts[1] ?? '';
        }
        return $user;
    }
    
    /**
     * Obtener usuario por email
     */
    public function obtenerPorEmail($email) {
        $query = "SELECT u.*, r.nombre_rol AS role 
                  FROM usuarios u
                  INNER JOIN roles r ON u.id_rol = r.id_rol
                  WHERE u.email = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $user = $resultado->fetch_assoc();
        
        if ($user) {
            $parts = explode(' ', $user['nombre_completo'], 2);
            $user['nombre'] = $parts[0] ?? '';
            $user['apellidos'] = $user['apellidos'] ?? $parts[1] ?? '';
        }
        return $user;
    }
    
    /**
     * Actualizar perfil
     */
    public function actualizarPerfil($id, $nombre, $apellidos, $telefono, $carrera, $ciclo) {
        $nombreCompleto = trim($nombre . ' ' . $apellidos);
        $query = "UPDATE usuarios SET nombre_completo = ?, apellidos = ?, telefono = ?, carrera = ?, ciclo = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssssi", $nombreCompleto, $apellidos, $telefono, $carrera, $ciclo, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Actualizar foto de perfil
     */
    public function actualizarFoto($id, $foto) {
        $query = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $foto, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Cambiar contraseña
     */
    public function cambiarPassword($id, $passwordActual, $passwordNueva) {
        $query = "SELECT password FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $user = $resultado->fetch_assoc();
        
        if (!$user || !password_verify($passwordActual, $user['password'])) {
            return false;
        }
        
        $query = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($passwordNueva, PASSWORD_BCRYPT);
        $stmt->bind_param("si", $hashedPassword, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Registrar con Google OAuth
     */
    public function registrarConGoogle($nombre, $apellidos, $email, $password, $foto = '', $googleId = '') {
        $nombreCompleto = trim($nombre . ' ' . $apellidos);

        $query = "INSERT INTO usuarios (nombre_completo, apellidos, email, password, foto_perfil, google_id, id_rol, universidad, carrera, ciclo, telefono) 
                  VALUES (?, ?, ?, ?, ?, ?, 3, 'Por completar', 'Por completar', '1', '')";
        
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // ssssssi (7 strings para los primeros 6 campos + id_rol int)
        $stmt->bind_param("ssssssi", 
            $nombreCompleto, 
            $apellidos, 
            $email, 
            $hashedPassword,
            $foto,
            $googleId,
            $id_rol_default // 7º parámetro
        );
        $id_rol_default = 3;

        return $stmt->execute();
    }
}
?>