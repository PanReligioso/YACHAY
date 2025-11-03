<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../MODELO/Tutoria.php';

class TutoriaController {
    
    private $tutoriaModel;
    
    public function __construct() {
        $this->tutoriaModel = new Tutoria();
    }
    
    /**
     * Crear nueva tutoría
     */
    public function crear() {
        if (!isLoggedIn()) {
            setFlash('error', 'Debes iniciar sesión para ofrecer tutorías');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtener datos
            $tutor_id = getUserId();
            $materia = sanitize($_POST['materia']);
            $descripcion = sanitize($_POST['descripcion']);
            $precio_hora = $_POST['precio_hora'];
            $modalidad = sanitize($_POST['modalidad']);
            $horario_disponible = sanitize($_POST['horario_disponible']);
            $universidad = sanitize($_POST['universidad']);
            $carrera = sanitize($_POST['carrera']);
            
            // Validaciones
            if (empty($materia) || empty($precio_hora) || empty($modalidad) || empty($horario_disponible)) {
                setFlash('error', 'Complete todos los campos obligatorios');
                redirect('MVC/VISTA/TUTORIAS/tutoria-crear.php');
                return;
            }
            
            if (!is_numeric($precio_hora) || $precio_hora < 0) {
                setFlash('error', 'El precio debe ser un número válido');
                redirect('MVC/VISTA/TUTORIAS/tutoria-crear.php');
                return;
            }
            
            // Crear tutoría
            if ($this->tutoriaModel->crear($tutor_id, $materia, $descripcion, $precio_hora, $modalidad, $horario_disponible, $universidad, $carrera)) {
                setFlash('success', '¡Tutoría creada exitosamente!');
                redirect('MVC/VISTA/TUTORIAS/tutorias.php');
            } else {
                setFlash('error', 'Error al crear la tutoría');
                redirect('MVC/VISTA/TUTORIAS/tutoria-crear.php');
            }
        }
    }
    
    /**
     * Actualizar tutoría
     */
    public function actualizar() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'];
            $user_id = getUserId();
            $materia = sanitize($_POST['materia']);
            $descripcion = sanitize($_POST['descripcion']);
            $precio_hora = $_POST['precio_hora'];
            $horario_disponible = sanitize($_POST['horario_disponible']);
            
            // Validaciones
            if (empty($materia) || empty($precio_hora)) {
                setFlash('error', 'Complete todos los campos obligatorios');
                redirect('MVC/VISTA/TUTORIAS/tutoria-editar.php?id=' . $id);
                return;
            }
            
            // Actualizar
            if ($this->tutoriaModel->actualizar($id, $user_id, $materia, $descripcion, $precio_hora, $horario_disponible)) {
                setFlash('success', 'Tutoría actualizada exitosamente');
                redirect('MVC/VISTA/TUTORIAS/tutoria-detalle.php?id=' . $id);
            } else {
                setFlash('error', 'Error al actualizar la tutoría');
                redirect('MVC/VISTA/TUTORIAS/tutoria-editar.php?id=' . $id);
            }
        }
    }
    
    /**
     * Eliminar tutoría
     */
    public function eliminar() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user_id = getUserId();
            
            if ($this->tutoriaModel->eliminar($id, $user_id)) {
                setFlash('success', 'Tutoría eliminada exitosamente');
            } else {
                setFlash('error', 'Error al eliminar la tutoría');
            }
            
            redirect('MVC/VISTA/PRINCIPAL/perfil.php');
        }
    }
}

// Procesar acciones
if (isset($_GET['action'])) {
    $controller = new TutoriaController();
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        redirect('MVC/VISTA/TUTORIAS/tutorias.php');
    }
}
?>