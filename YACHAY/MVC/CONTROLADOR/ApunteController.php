<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/funciones.php';
require_once __DIR__ . '/../MODELO/Apunte.php';

class ApunteController {
    
    private $apunteModel;
    
    public function __construct() {
        $this->apunteModel = new Apunte();
    }
    
    /**
     * Subir nuevo apunte
     */
    public function subir() {
        if (!isLoggedIn()) {
            setFlash('error', 'Debes iniciar sesión para subir apuntes');
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtener datos
            $user_id = getUserId();
            $titulo = sanitize($_POST['titulo']);
            $descripcion = sanitize($_POST['descripcion']);
            $curso = sanitize($_POST['curso']);
            $tema = sanitize($_POST['tema']);
            $ciclo = sanitize($_POST['ciclo']);
            $universidad = sanitize($_POST['universidad']);
            $carrera = sanitize($_POST['carrera']);
            
            // Validaciones
            if (empty($titulo) || empty($curso) || empty($ciclo)) {
                setFlash('error', 'Complete todos los campos obligatorios');
                redirect('MVC/VISTA/APUNTES/apunte-subir.php');
                return;
            }
            
            // Validar archivo PDF
            if (!isset($_FILES['archivo_pdf']) || $_FILES['archivo_pdf']['error'] !== 0) {
                setFlash('error', 'Debe seleccionar un archivo PDF');
                redirect('MVC/VISTA/APUNTES/apunte-subir.php');
                return;
            }
            
            // Validar tipo
            if ($_FILES['archivo_pdf']['type'] !== 'application/pdf') {
                setFlash('error', 'Solo se permiten archivos PDF');
                redirect('MVC/VISTA/APUNTES/apunte-subir.php');
                return;
            }
            
            // Validar tamaño (máx 10MB)
            if ($_FILES['archivo_pdf']['size'] > 10485760) {
                setFlash('error', 'El archivo es muy grande (máximo 10MB)');
                redirect('MVC/VISTA/APUNTES/apunte-subir.php');
                return;
            }
            
            // Crear directorio si no existe
            $uploadDir = __DIR__ . '/../VISTA/IMG/uploads/apuntes/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generar nombre único
            $extension = pathinfo($_FILES['archivo_pdf']['name'], PATHINFO_EXTENSION);
            $archivo_pdf = uniqid() . '_' . time() . '.' . $extension;
            $rutaDestino = $uploadDir . $archivo_pdf;
            
            // Mover archivo
            if (!move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $rutaDestino)) {
                setFlash('error', 'Error al subir el archivo');
                redirect('MVC/VISTA/APUNTES/apunte-subir.php');
                return;
            }
            
            $tamano_archivo = $_FILES['archivo_pdf']['size'];
            
            // Crear apunte
            if ($this->apunteModel->crear($user_id, $titulo, $descripcion, $archivo_pdf, $tamano_archivo, $curso, $tema, $ciclo, $universidad, $carrera)) {
                setFlash('success', '¡Apunte subido exitosamente!');
                redirect('MVC/VISTA/APUNTES/apuntes.php');
            } else {
                setFlash('error', 'Error al subir el apunte');
                redirect('MVC/VISTA/APUNTES/apunte-subir.php');
            }
        }
    }
    
    /**
     * Descargar apunte
     */
    public function descargar() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Obtener datos del apunte
            $apunte = $this->apunteModel->obtenerPorId($id);
            
            if (!$apunte) {
                setFlash('error', 'Apunte no encontrado');
                redirect('MVC/VISTA/APUNTES/apuntes.php');
                return;
            }
            
            $rutaArchivo = __DIR__ . '/../VISTA/IMG/uploads/apuntes/' . $apunte['archivo_pdf'];
            
            if (!file_exists($rutaArchivo)) {
                setFlash('error', 'Archivo no encontrado');
                redirect('MVC/VISTA/APUNTES/apuntes.php');
                return;
            }
            
            // Incrementar contador de descargas
            $this->apunteModel->incrementarDescargas($id);
            
            // Forzar descarga
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $apunte['titulo'] . '.pdf"');
            header('Content-Length: ' . filesize($rutaArchivo));
            readfile($rutaArchivo);
            exit;
        }
    }
    
    /**
     * Eliminar apunte
     */
    public function eliminar() {
        if (!isLoggedIn()) {
            redirect('MVC/VISTA/PRINCIPAL/login.php');
            return;
        }
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user_id = getUserId();
            
            // Obtener apunte para eliminar archivo
            $apunte = $this->apunteModel->obtenerPorId($id);
            
            if ($apunte && $apunte['user_id'] == $user_id) {
                
                // Eliminar archivo físico
                $rutaArchivo = __DIR__ . '/../VISTA/IMG/uploads/apuntes/' . $apunte['archivo_pdf'];
                if (file_exists($rutaArchivo)) {
                    unlink($rutaArchivo);
                }
                
                // Eliminar de BD
                if ($this->apunteModel->eliminar($id, $user_id)) {
                    setFlash('success', 'Apunte eliminado exitosamente');
                } else {
                    setFlash('error', 'Error al eliminar el apunte');
                }
            } else {
                setFlash('error', 'No tienes permiso para eliminar este apunte');
            }
            
            redirect('MVC/VISTA/PRINCIPAL/perfil.php');
        }
    }
}

// Procesar acciones
if (isset($_GET['action'])) {
    $controller = new ApunteController();
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        redirect('MVC/VISTA/APUNTES/apuntes.php');
    }
}
?>