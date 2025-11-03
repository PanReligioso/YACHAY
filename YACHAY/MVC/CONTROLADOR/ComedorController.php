<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../MODELO/Comedor.php';

class ComedorController {
    
    private $comedorModel;
    
    public function __construct() {
        $this->comedorModel = new Comedor();
    }
    
    /**
     * Listar todos los comedores (página principal)
     */
    public function listar() {
        // Obtener filtros
        $universidad = $_GET['universidad'] ?? '';
        $tipo_comida = $_GET['tipo_comida'] ?? '';
        $precio_max = $_GET['precio_max'] ?? '';
        $busqueda = $_GET['busqueda'] ?? '';
        
        // Obtener comedores con filtros
        $comedores = $this->comedorModel->obtenerTodos($universidad, $tipo_comida, $precio_max, $busqueda);
        
        // Inicializar como array vacío si es null
        $comedores = $comedores ?? [];
        
        // Preparar datos para JavaScript (Google Maps)
        $comedoresJSON = json_encode(array_map(function($c) {
            return [
                'id' => $c['id'],
                'nombre' => $c['nombre'],
                'direccion' => $c['direccion'],
                'latitud' => $c['latitud'],
                'longitud' => $c['longitud'],
                'precio_menu_min' => $c['precio_menu_min'],
                'universidad_cercana' => $c['universidad_cercana']
            ];
        }, $comedores));
        
        $pageTitle = 'Comedores Económicos - YACHAY';
        
        // Incluir la vista
        include __DIR__ . '/../VISTA/PRINCIPAL/COMEDORES/comedores.php';
    }
    
    /**
     * Crear nuevo comedor (solo admins o dueños verificados)
     */
    public function crear() {
        if (!isLoggedIn()) {
            setFlash('error', 'Debes iniciar sesión');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtener datos
            $nombre = sanitize($_POST['nombre']);
            $descripcion = sanitize($_POST['descripcion']);
            $direccion = sanitize($_POST['direccion']);
            $latitud = $_POST['latitud'];
            $longitud = $_POST['longitud'];
            $universidad_cercana = sanitize($_POST['universidad_cercana']);
            $precio_menu_min = $_POST['precio_menu_min'];
            $precio_menu_max = $_POST['precio_menu_max'];
            $horario_apertura = $_POST['horario_apertura'];
            $horario_cierre = $_POST['horario_cierre'];
            $dias_atencion = sanitize($_POST['dias_atencion']);
            $telefono = sanitize($_POST['telefono']);
            $tipo_comida = sanitize($_POST['tipo_comida']);
            $menu_dia = sanitize($_POST['menu_dia']);
            
            // Validaciones
            if (empty($nombre) || empty($direccion) || empty($precio_menu_min)) {
                setFlash('error', 'Complete todos los campos obligatorios');
                redirect('MVC/VISTA/COMEDORES/comedor-crear.php');
                return;
            }
            
            // Subir foto (opcional)
            $foto = 'default-comedor.jpg';
            
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                
                // Validar tipo
                $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($_FILES['foto']['type'], $tiposPermitidos)) {
                    setFlash('error', 'Solo se permiten imágenes JPG, JPEG o PNG');
                    redirect('MVC/VISTA/COMEDORES/comedor-crear.php');
                    return;
                }
                
                // Crear directorio
                $uploadDir = __DIR__ . '/../VISTA/IMG/uploads/comedores/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generar nombre único
                $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = uniqid() . '_' . time() . '.' . $extension;
                $rutaDestino = $uploadDir . $foto;
                
                move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino);
            }
            
            // Crear comedor
            if ($this->comedorModel->crear($nombre, $descripcion, $direccion, $latitud, $longitud, $universidad_cercana, $precio_menu_min, $precio_menu_max, $horario_apertura, $horario_cierre, $dias_atencion, $telefono, $tipo_comida, $menu_dia, $foto)) {
                setFlash('success', '¡Comedor registrado exitosamente!');
                redirect('MVC/CONTROLADOR/ComedorController.php?action=listar');
            } else {
                setFlash('error', 'Error al registrar el comedor');
                redirect('MVC/VISTA/COMEDORES/comedor-crear.php');
            }
        }
    }
    
    /**
     * Actualizar comedor
     */
    public function actualizar() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'];
            $nombre = sanitize($_POST['nombre']);
            $descripcion = sanitize($_POST['descripcion']);
            $precio_menu_min = $_POST['precio_menu_min'];
            $precio_menu_max = $_POST['precio_menu_max'];
            $horario_apertura = $_POST['horario_apertura'];
            $horario_cierre = $_POST['horario_cierre'];
            $menu_dia = sanitize($_POST['menu_dia']);
            
            // Actualizar
            if ($this->comedorModel->actualizar($id, $nombre, $descripcion, $precio_menu_min, $precio_menu_max, $horario_apertura, $horario_cierre, $menu_dia)) {
                setFlash('success', 'Comedor actualizado exitosamente');
                redirect('MVC/VISTA/COMEDORES/comedor-detalle.php?id=' . $id);
            } else {
                setFlash('error', 'Error al actualizar el comedor');
                redirect('MVC/VISTA/COMEDORES/comedor-editar.php?id=' . $id);
            }
        }
    }
    
    /**
     * Eliminar comedor (solo admins)
     */
    public function eliminar() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlash('error', 'No tienes permisos para realizar esta acción');
            redirect('MVC/CONTROLADOR/ComedorController.php?action=listar');
            return;
        }
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            if ($this->comedorModel->eliminar($id)) {
                setFlash('success', 'Comedor eliminado exitosamente');
            } else {
                setFlash('error', 'Error al eliminar el comedor');
            }
            
            redirect('MVC/CONTROLADOR/ComedorController.php?action=listar');
        }
    }
}

// Procesar acciones
$action = $_GET['action'] ?? 'listar'; // Por defecto listar

$controller = new ComedorController();

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->listar();
}
?>