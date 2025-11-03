<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../MODELO/Libro.php';

class LibroController {
    
    private $libroModel;
    
    public function __construct() {
        $this->libroModel = new Libro();
    }
    
    /**
     * Crear nuevo libro
     */
    public function crear() {
        // Verificar que el usuario esté logueado
        if (!isLoggedIn()) {
            setFlash('error', 'Debes iniciar sesión para publicar un libro');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtener datos
            $user_id = getUserId();
            $titulo = sanitize($_POST['titulo']);
            $autor = sanitize($_POST['autor']);
            $editorial = sanitize($_POST['editorial']);
            $precio = $_POST['precio'];
            $precio_original = $_POST['precio_original'];
            $estado = sanitize($_POST['estado']);
            $universidad = sanitize($_POST['universidad']);
            $carrera = sanitize($_POST['carrera']);
            $curso = sanitize($_POST['curso']);
            $ciclo = sanitize($_POST['ciclo']);
            $descripcion = sanitize($_POST['descripcion']);
            
            // Validaciones
            if (empty($titulo) || empty($autor) || empty($precio) || empty($estado)) {
                setFlash('error', 'Complete todos los campos obligatorios');
                redirect('MVC/VISTA/LIBROS/libro-crear.php');
                return;
            }
            
            if (!is_numeric($precio) || $precio <= 0) {
                setFlash('error', 'El precio debe ser un número válido');
                redirect('MVC/VISTA/LIBROS/libro-crear.php');
                return;
            }
            
            // Subir foto
            $foto = 'default-book.jpg';
            
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                
                // Validar tipo de archivo
                $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($_FILES['foto']['type'], $tiposPermitidos)) {
                    setFlash('error', 'Solo se permiten imágenes JPG, JPEG o PNG');
                    redirect('MVC/VISTA/LIBROS/libro-crear.php');
                    return;
                }
                
                // Validar tamaño (máx 5MB)
                if ($_FILES['foto']['size'] > 5242880) {
                    setFlash('error', 'La imagen es muy grande (máximo 5MB)');
                    redirect('MVC/VISTA/LIBROS/libro-crear.php');
                    return;
                }
                
                // Crear directorio si no existe
                $uploadDir = __DIR__ . '/../VISTA/IMG/uploads/libros/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generar nombre único
                $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = uniqid() . '_' . time() . '.' . $extension;
                $rutaDestino = $uploadDir . $foto;
                
                // Mover archivo
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
                    setFlash('error', 'Error al subir la imagen');
                    redirect('MVC/VISTA/LIBROS/libro-crear.php');
                    return;
                }
            }
            
            // Crear libro
            if ($this->libroModel->crear($user_id, $titulo, $autor, $editorial, $precio, $precio_original, $estado, $foto, $universidad, $carrera, $curso, $ciclo, $descripcion)) {
                setFlash('success', '¡Libro publicado exitosamente!');
                redirect('MVC/VISTA/LIBROS/libros.php');
            } else {
                setFlash('error', 'Error al publicar el libro. Intenta nuevamente');
                redirect('MVC/VISTA/LIBROS/libro-crear.php');
            }
        }
    }
    
    /**
     * Editar libro
     */
    public function editar() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'];
            $user_id = getUserId();
            $titulo = sanitize($_POST['titulo']);
            $autor = sanitize($_POST['autor']);
            $precio = $_POST['precio'];
            $estado = sanitize($_POST['estado']);
            $descripcion = sanitize($_POST['descripcion']);
            
            // Validaciones
            if (empty($titulo) || empty($autor) || empty($precio)) {
                setFlash('error', 'Complete todos los campos obligatorios');
                redirect('MVC/VISTA/LIBROS/libro-editar.php?id=' . $id);
                return;
            }
            
            // Actualizar
            if ($this->libroModel->actualizar($id, $user_id, $titulo, $autor, $precio, $estado, $descripcion)) {
                setFlash('success', 'Libro actualizado exitosamente');
                redirect('MVC/VISTA/LIBROS/libros-detalles.php?id=' . $id);
            } else {
                setFlash('error', 'Error al actualizar el libro');
                redirect('MVC/VISTA/LIBROS/libro-editar.php?id=' . $id);
            }
        }
    }
    
    /**
     * Eliminar libro
     */
    public function eliminar() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user_id = getUserId();
            
            if ($this->libroModel->eliminar($id, $user_id)) {
                setFlash('success', 'Libro eliminado exitosamente');
            } else {
                setFlash('error', 'Error al eliminar el libro');
            }
            
            redirect('MVC/VISTA/PRINCIPAL/perfil.php');
        }
    }
    
    /**
     * Marcar como vendido
     */
    public function marcarVendido() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user_id = getUserId();
            
            if ($this->libroModel->marcarVendido($id, $user_id)) {
                setFlash('success', 'Libro marcado como vendido');
            } else {
                setFlash('error', 'Error al actualizar el libro');
            }
            
            redirect('MVC/VISTA/PRINCIPAL/perfil.php');
        }
    }
}

// Procesar acciones
if (isset($_GET['action'])) {
    $controller = new LibroController();
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        redirect('MVC/VISTA/LIBROS/libros.php');
    }
}
?>