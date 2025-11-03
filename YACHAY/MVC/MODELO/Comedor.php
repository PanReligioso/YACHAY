<?php
require_once __DIR__ . '/../../config/database.php';

class Comedor {
    
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
    
    /**
     * Crear nuevo comedor
     */
    public function crear($nombre, $descripcion, $direccion, $latitud, $longitud, $universidad_cercana, $precio_menu_min, $precio_menu_max, $horario_apertura, $horario_cierre, $dias_atencion, $telefono, $tipo_comida, $menu_dia, $foto = 'default-comedor.jpg') {
        $query = "INSERT INTO comedores (nombre, descripcion, direccion, latitud, longitud, universidad_cercana, precio_menu_min, precio_menu_max, horario_apertura, horario_cierre, dias_atencion, telefono, tipo_comida, menu_dia, foto) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        // Tipos ajustados: sssddsssssssis
        $stmt->bind_param("ssddsssssssss", 
            $nombre, $descripcion, $direccion, $latitud, $longitud, 
            $universidad_cercana, $precio_menu_min, $precio_menu_max, 
            $horario_apertura, $horario_cierre, $dias_atencion, 
            $telefono, $tipo_comida, $menu_dia, $foto
        );
        
        return $stmt->execute();
    }
    
    /**
     * Obtener todos los comedores
     */
    public function obtenerTodos($universidad = '', $tipo_comida = '', $precio_max = '', $busqueda = '', $limit = 20, $offset = 0) {
        $query = "SELECT * FROM comedores WHERE activo = 1";
        
        $params = [];
        $types = "";
        
        if (!empty($universidad)) {
            $query .= " AND universidad_cercana = ?";
            $params[] = $universidad;
            $types .= "s";
        }
        
        // CORRECCIÓN: Agregar filtro por tipo_comida
        if (!empty($tipo_comida)) {
            $query .= " AND tipo_comida = ?";
            $params[] = $tipo_comida;
            $types .= "s";
        }
        
        // CORRECCIÓN: Agregar filtro por precio_max (usando precio_menu_min como referencia)
        if (!empty($precio_max)) {
            $query .= " AND precio_menu_min <= ?";
            $params[] = $precio_max;
            $types .= "d";
        }

        // CORRECCIÓN: Usar busqueda por nombre
        if (!empty($busqueda)) {
            $query .= " AND nombre LIKE ?";
            $params[] = "%{$busqueda}%";
            $types .= "s";
        }
        
        $query .= " ORDER BY valoracion_promedio DESC, created_at DESC LIMIT ? OFFSET ?";
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
        
        $comedores = [];
        while ($row = $resultado->fetch_assoc()) {
            $comedores[] = $row;
        }
        
        return $comedores;
    }
    
    /**
     * Obtener comedor por ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM comedores WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    /**
     * Actualizar comedor
     */
    public function actualizar($id, $nombre, $descripcion, $precio_menu_min, $precio_menu_max, $horario_apertura, $horario_cierre, $menu_dia) {
        $query = "UPDATE comedores SET nombre = ?, descripcion = ?, precio_menu_min = ?, precio_menu_max = ?, horario_apertura = ?, horario_cierre = ?, menu_dia = ? 
                  WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssddsssi", $nombre, $descripcion, $precio_menu_min, $precio_menu_max, $horario_apertura, $horario_cierre, $menu_dia, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar comedor
     */
    public function eliminar($id) {
        $query = "DELETE FROM comedores WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    /**
     * Contar total de comedores
     */
    public function contarTotal($universidad = '', $tipo_comida = '', $precio_max = '', $busqueda = '') {
        $query = "SELECT COUNT(*) as total FROM comedores WHERE activo = 1";
        
        $params = [];
        $types = "";
        
        if (!empty($universidad)) {
            $query .= " AND universidad_cercana = ?";
            $params[] = $universidad;
            $types .= "s";
        }

        // CORRECCIÓN: Agregar filtro por tipo_comida
        if (!empty($tipo_comida)) {
            $query .= " AND tipo_comida = ?";
            $params[] = $tipo_comida;
            $types .= "s";
        }
        
        // CORRECCIÓN: Agregar filtro por precio_max
        if (!empty($precio_max)) {
            $query .= " AND precio_menu_min <= ?";
            $params[] = $precio_max;
            $types .= "d";
        }
        
        // CORRECCIÓN: Usar busqueda por nombre
        if (!empty($busqueda)) {
            $query .= " AND nombre LIKE ?";
            $params[] = "%{$busqueda}%";
            $types .= "s";
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