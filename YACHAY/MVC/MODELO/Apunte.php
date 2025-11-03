<?php
require_once __DIR__ . '/../../config/database.php';

class Apunte {
    
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
     * Crear nuevo apunte
     */
    public function crear($user_id, $titulo, $descripcion, $archivo_pdf, $tamano_archivo, $curso_id, $tema, $ciclo, $universidad, $carrera) {
        // CORRECCIÓN: Usar id_usuario_subida, id_curso (FK), url_drive y tipo_material. 
        // Se omiten tamano_archivo, tema, ciclo, universidad, carrera ya que no están en la tabla 'apuntes'.
        $query = "INSERT INTO apuntes (id_usuario_subida, titulo, descripcion, id_curso, url_drive, tipo_material) 
                  VALUES (?, ?, ?, ?, ?, 'apuntes')"; 
        
        $stmt = $this->db->prepare($query);
        // Asumiendo que $curso_id es el ID numérico del curso.
        $stmt->bind_param("isssis", 
            $user_id, // Mapea a id_usuario_subida
            $titulo, 
            $descripcion, 
            $curso_id, // Mapea a id_curso
            $archivo_pdf, // Mapea a url_drive
            // 'apuntes' es el valor fijo para tipo_material
        );
        
        return $stmt->execute();
    }
    
    /**
     * Obtener todos los apuntes con filtros
     */
    public function obtenerTodos($universidad = '', $curso = '', $busqueda = '', $limit = 12, $offset = 0) {
        // CORRECCIÓN: Usar id_usuario_subida, nombre_completo, id_apunte, estado_validacion, y JOIN a cursos.
        $query = "SELECT a.id_apunte, a.titulo, a.descripcion, a.fecha_subida, a.descargas,
                         u.nombre_completo AS autor_nombre, c.nombre_curso AS curso_nombre
                  FROM apuntes a 
                  INNER JOIN usuarios u ON a.id_usuario_subida = u.id_usuario 
                  INNER JOIN cursos c ON a.id_curso = c.id_curso
                  WHERE a.estado_validacion = 'aprobado'"; // Solo mostrar aprobados
        
        $params = [];
        $types = "";
        
        // Filtro por nombre de curso (columna nombre_curso de la tabla cursos)
        if (!empty($curso)) {
            $query .= " AND c.nombre_curso LIKE ?";
            $params[] = "%{$curso}%";
            $types .= "s";
        }
        
        if (!empty($busqueda)) {
            // CORRECCIÓN: Usar sintaxis MATCH AGAINST para la búsqueda FULLTEXT
            $query .= " AND MATCH(a.titulo, a.descripcion) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $busqueda;
            $types .= "s";
        }
        
        $query .= " ORDER BY a.fecha_subida DESC LIMIT ? OFFSET ?";
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
        
        $apuntes = [];
        while ($row = $resultado->fetch_assoc()) {
            $apuntes[] = $row;
        }
        
        return $apuntes;
    }
    
    /**
     * Obtener apunte por ID
     */
    public function obtenerPorId($id) {
        // CORRECCIÓN: Usar id_apunte, id_usuario_subida, nombre_completo
        $query = "SELECT a.*, u.nombre_completo AS autor_nombre 
                  FROM apuntes a 
                  INNER JOIN usuarios u ON a.id_usuario_subida = u.id_usuario 
                  WHERE a.id_apunte = ? 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    /**
     * Obtener apuntes por usuario
     */
    public function obtenerPorUsuario($user_id) {
        // CORRECCIÓN: Usar id_usuario_subida y nombre_completo
        $query = "SELECT a.*, u.nombre_completo AS autor_nombre 
                  FROM apuntes a 
                  INNER JOIN usuarios u ON a.id_usuario_subida = u.id_usuario 
                  WHERE a.id_usuario_subida = ? 
                  ORDER BY a.fecha_subida DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        
        $apuntes = [];
        while ($row = $resultado->fetch_assoc()) {
            $apuntes[] = $row;
        }
        
        return $apuntes;
    }
    
    /**
     * Incrementar contador de descargas
     */
    public function incrementarDescargas($id) {
        // CORRECCIÓN: Usar id_apunte
        $query = "UPDATE apuntes SET descargas = descargas + 1 WHERE id_apunte = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar apunte
     */
    public function eliminar($id, $user_id) {
        // CORRECCIÓN: Usar id_apunte y id_usuario_subida
        $query = "DELETE FROM apuntes WHERE id_apunte = ? AND id_usuario_subida = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $user_id);
        
        return $stmt->execute();
    }
    
    /**
     * Contar total de apuntes
     */
    public function contarTotal($universidad = '', $curso = '', $busqueda = '') {
        // CORRECCIÓN: Usar id_usuario_subida, id_curso, estado_validacion y sintaxis MATCH AGAINST
        $query = "SELECT COUNT(*) as total 
                  FROM apuntes a 
                  INNER JOIN usuarios u ON a.id_usuario_subida = u.id_usuario 
                  INNER JOIN cursos c ON a.id_curso = c.id_curso
                  WHERE a.estado_validacion = 'aprobado'";
        
        $params = [];
        $types = "";
        
        if (!empty($curso)) {
            $query .= " AND c.nombre_curso LIKE ?";
            $params[] = "%{$curso}%";
            $types .= "s";
        }
        
        if (!empty($busqueda)) {
            $query .= " AND MATCH(a.titulo, a.descripcion) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $busqueda;
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