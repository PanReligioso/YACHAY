<?php
require_once __DIR__ . '/../../config/database.php';

class Tutoria {
    
    private $db;
    
    public function __construct() {
        $this->db = Conectar::conexion();
    }
    
    /**
     * Función auxiliar para bind_param (requiere referencias)
     */
    private function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) //PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }    
        return $arr;
    }
    
    public function crear($tutor_id, $materia, $descripcion, $precio_hora, $horario_disponible, $modalidad) {
        $query = "INSERT INTO tutorias (tutor_id, materia, descripcion, precio_hora, horario_disponible, modalidad) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issdss", $tutor_id, $materia, $descripcion, $precio_hora, $horario_disponible, $modalidad);
        
        return $stmt->execute();
    }
    
    public function obtenerTodas($materia = '', $universidad = '', $precio_max = '', $busqueda = '', $limit = 20, $offset = 0) {
        // CORRECCIÓN: Usar id_usuario, nombre_completo
        $query = "SELECT t.*, 
                         u.nombre_completo AS tutor_nombre, 
                         u.apellidos AS tutor_apellidos, 
                         u.email AS tutor_email,
                         u.carrera AS tutor_carrera,
                         u.universidad AS tutor_universidad
                  FROM tutorias t 
                  INNER JOIN usuarios u ON t.tutor_id = u.id_usuario 
                  WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($materia)) {
            $query .= " AND t.materia LIKE ?";
            $params[] = "%{$materia}%";
            $types .= "s";
        }
        
        if (!empty($universidad)) {
            $query .= " AND u.universidad = ?";
            $params[] = $universidad;
            $types .= "s";
        }
        
        if (!empty($precio_max)) {
            $query .= " AND t.precio_hora <= ?";
            $params[] = $precio_max;
            $types .= "d";
        }
        
        if (!empty($busqueda)) {
            // CORRECCIÓN: Usar nombre_completo y apellidos para la búsqueda en usuarios
            $query .= " AND (t.materia LIKE ? OR t.descripcion LIKE ? OR u.nombre_completo LIKE ? OR u.apellidos LIKE ?)";
            $busquedaParam = "%{$busqueda}%";
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $types .= "ssss";
        }
        
        $query .= " ORDER BY t.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->db->prepare($query);
        
        if (!empty($params)) {
            $bind_params = array_merge([$types], $params);
            call_user_func_array([$stmt, 'bind_param'], $this->refValues($bind_params));
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $tutorias = [];
        while ($row = $resultado->fetch_assoc()) {
            $tutorias[] = $row;
        }
        
        return $tutorias;
    }
    
    public function obtenerPorId($id) {
        // CORRECCIÓN: Usar id_usuario, nombre_completo, apellidos
        $query = "SELECT t.*, 
                         u.nombre_completo AS tutor_nombre, 
                         u.apellidos AS tutor_apellidos, 
                         u.email AS tutor_email,
                         u.carrera AS tutor_carrera,
                         u.universidad AS tutor_universidad,
                         u.ciclo AS tutor_ciclo
                  FROM tutorias t 
                  INNER JOIN usuarios u ON t.tutor_id = u.id_usuario 
                  WHERE t.id = ? 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    public function obtenerPorTutor($tutor_id) {
        // CORRECCIÓN: Usar id_usuario, nombre_completo, apellidos
        $query = "SELECT t.*, 
                         u.nombre_completo AS tutor_nombre, 
                         u.apellidos AS tutor_apellidos
                  FROM tutorias t 
                  INNER JOIN usuarios u ON t.tutor_id = u.id_usuario 
                  WHERE t.tutor_id = ? 
                  ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $tutor_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        
        $tutorias = [];
        while ($row = $resultado->fetch_assoc()) {
            $tutorias[] = $row;
        }
        
        return $tutorias;
    }
    
    public function actualizar($id, $tutor_id, $materia, $descripcion, $precio_hora, $horario_disponible) {
        $query = "UPDATE tutorias 
                  SET materia = ?, descripcion = ?, precio_hora = ?, horario_disponible = ? 
                  WHERE id = ? AND tutor_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssdsii", $materia, $descripcion, $precio_hora, $horario_disponible, $id, $tutor_id);
        
        return $stmt->execute();
    }
    
    public function eliminar($id, $tutor_id) {
        $query = "DELETE FROM tutorias WHERE id = ? AND tutor_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $tutor_id);
        
        return $stmt->execute();
    }
    
    public function contarTotal($materia = '', $universidad = '', $precio_max = '', $busqueda = '') {
        // CORRECCIÓN: Usar id_usuario, nombre_completo, apellidos
        $query = "SELECT COUNT(*) as total 
                  FROM tutorias t 
                  INNER JOIN usuarios u ON t.tutor_id = u.id_usuario 
                  WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($materia)) {
            $query .= " AND t.materia LIKE ?";
            $params[] = "%{$materia}%";
            $types .= "s";
        }
        
        if (!empty($universidad)) {
            $query .= " AND u.universidad = ?";
            $params[] = $universidad;
            $types .= "s";
        }
        
        if (!empty($precio_max)) {
            $query .= " AND t.precio_hora <= ?";
            $params[] = $precio_max;
            $types .= "d";
        }
        
        if (!empty($busqueda)) {
            $query .= " AND (t.materia LIKE ? OR t.descripcion LIKE ? OR u.nombre_completo LIKE ? OR u.apellidos LIKE ?)";
            $busquedaParam = "%{$busqueda}%";
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $types .= "ssss";
        }
        
        $stmt = $this->db->prepare($query);
        
        if (!empty($params)) {
            $bind_params = array_merge([$types], $params);
            call_user_func_array([$stmt, 'bind_param'], $this->refValues($bind_params));
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        
        return $row['total'];
    }
}
?>