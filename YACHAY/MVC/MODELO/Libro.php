<?php
require_once __DIR__ . '/../../config/database.php';

class Libro {
    
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
     * Crear nuevo libro
     */
    public function crear($user_id, $titulo, $autor, $editorial, $precio, $precio_original, $estado, $foto, $universidad, $carrera, $curso, $ciclo, $descripcion) {
        // CORRECCIÓN: Usar id_usuario_subida, autor_libro, url_drive (para la foto), id_categoria.
        // Los campos precio, precio_original, universidad, carrera, curso, ciclo se ignoran ya que no existen en la tabla 'libros'.
        // El campo 'estado' se mapea a id_categoria.
        
        $query = "INSERT INTO libros (id_usuario_subida, titulo, autor_libro, editorial, descripcion, url_drive, id_categoria, anio_publicacion) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, 2025)"; 
        
        $stmt = $this->db->prepare($query);
        $id_categoria = 1; // **IMPORTANTE: Debes obtener el ID de categoría real en el Controller/Vista**
        
        // issssii (i: id_usuario, s: strings, i: id_categoria, i: anio)
        $stmt->bind_param("issssii", 
            $user_id, 
            $titulo, 
            $autor, // Mapea a autor_libro
            $editorial, 
            $descripcion,
            $foto, // Mapea a url_drive
            $id_categoria // Mapea al estado/categoría
        );
        
        return $stmt->execute();
    }
    
    /**
     * Obtener todos los libros con filtros
     */
    public function obtenerTodos($universidad = '', $carrera = '', $busqueda = '', $limit = 12, $offset = 0) {
        // CORRECCIÓN: Usar id_usuario_subida, nombre_completo, estado_validacion y sintaxis MATCH AGAINST
        $query = "SELECT l.id_libro, l.titulo, l.autor_libro, l.descripcion, l.vistas, l.fecha_subida,
                  u.nombre_completo AS vendedor_nombre, u.telefono AS vendedor_telefono 
                  FROM libros l 
                  INNER JOIN usuarios u ON l.id_usuario_subida = u.id_usuario 
                  WHERE l.estado_validacion = 'aprobado'"; // Disponible se mapea a 'aprobado'
        
        $params = [];
        $types = "";
        
        // Los filtros universidad/carrera se ignoran aquí ya que no están en la tabla 'libros'.

        if (!empty($busqueda)) {
            // CORRECCIÓN: Usar MATCH AGAINST para FULLTEXT en las columnas correctas
            $query .= " AND MATCH(l.titulo, l.autor_libro, l.descripcion) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $busqueda;
            $types .= "s";
        }
        
        $query .= " ORDER BY l.fecha_subida DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->db->prepare($query);
        
        $bind_params = array_merge([$types], $params);
        if (!empty($params)) {
             call_user_func_array([$stmt, 'bind_param'], $this->refValues($bind_params));
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $libros = [];
        while ($row = $resultado->fetch_assoc()) {
            $libros[] = $row;
        }
        
        return $libros;
    }
    
    /**
     * Obtener libro por ID
     */
    public function obtenerPorId($id) {
        // CORRECCIÓN: Usar id_libro, id_usuario_subida, nombre_completo y apellidos
        $query = "SELECT l.*, u.nombre_completo AS vendedor_nombre, u.apellidos AS vendedor_apellidos, 
                  u.telefono AS vendedor_telefono, u.email AS vendedor_email 
                  FROM libros l 
                  INNER JOIN usuarios u ON l.id_usuario_subida = u.id_usuario 
                  WHERE l.id_libro = ? LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $libro = $resultado->fetch_assoc();
        
        // Incrementar vistas (columna 'vistas' existe)
        if ($libro) {
            $this->incrementarVistas($id);
        }
        
        return $libro;
    }
    
    /**
     * Obtener libros por usuario
     */
    public function obtenerPorUsuario($user_id) {
        // CORRECCIÓN: Usar id_usuario_subida y fecha_subida
        $query = "SELECT * FROM libros WHERE id_usuario_subida = ? ORDER BY fecha_subida DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        
        $libros = [];
        while ($row = $resultado->fetch_assoc()) {
            $libros[] = $row;
        }
        
        return $libros;
    }
    
    /**
     * Actualizar libro
     */
    public function actualizar($id, $user_id, $titulo, $autor, $precio, $estado, $descripcion) {
        // CORRECCIÓN: Usar id_libro, id_usuario_subida, autor_libro. 
        // Se omiten precio y estado porque no son campos de libro en su esquema.
        $query = "UPDATE libros SET titulo = ?, autor_libro = ?, descripcion = ? 
                  WHERE id_libro = ? AND id_usuario_subida = ?";
        
        $stmt = $this->db->prepare($query);
        // El bind_param usa los tipos para los parámetros restantes (s:titulo, s:autor, s:descripcion, i:id_libro, i:id_usuario_subida)
        $stmt->bind_param("sssi", $titulo, $autor, $descripcion, $id, $user_id);
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar libro
     */
    public function eliminar($id, $user_id) {
        // CORRECCIÓN: Usar id_libro y id_usuario_subida
        $query = "DELETE FROM libros WHERE id_libro = ? AND id_usuario_subida = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $user_id);
        
        return $stmt->execute();
    }
    
    /**
     * Marcar como vendido
     */
    public function marcarVendido($id, $user_id) {
        // CORRECCIÓN: Simular "vendido" cambiando estado_validacion a 'rechazado'
        $query = "UPDATE libros SET estado_validacion = 'rechazado', comentario_validacion = 'Marcado como vendido por el usuario' 
                  WHERE id_libro = ? AND id_usuario_subida = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id, $user_id);
        
        return $stmt->execute();
    }
    
    /**
     * Incrementar vistas
     */
    private function incrementarVistas($id) {
        // CORRECCIÓN: Usar id_libro
        $query = "UPDATE libros SET vistas = vistas + 1 WHERE id_libro = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    /**
     * Contar total de libros
     */
    public function contarTotal($universidad = '', $carrera = '', $busqueda = '') {
        // CORRECCIÓN: Usar estado_validacion = 'aprobado' y sintaxis MATCH AGAINST
        $query = "SELECT COUNT(*) as total FROM libros WHERE estado_validacion = 'aprobado'";
        
        $params = [];
        $types = "";
        
        if (!empty($busqueda)) {
            $query .= " AND MATCH(titulo, autor_libro, descripcion) AGAINST(? IN NATURAL LANGUAGE MODE)";
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