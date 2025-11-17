<?php

namespace App\Services;

use PDO;
use PDOException;

/**
 * Clase LibroServicio - Gestiona toda la lógica de datos de Libros
 * 
 * Esta clase es la ÚNICA que interactúa con la tabla 'libros' en MySQL.
 * El controlador solo llama a los métodos de este servicio.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0.0
 */
class LibroServicio extends ModeloBase
{
    protected string $tabla = 'libros';
    protected string $clavePrimaria = 'id';

    /**
     * Obtiene libros por categoría
     */
    public function obtenerLibrosPorCategoria(int $idCategoria, int $pagina = 1, int $porPagina = 15): array
    {
        try {
            $offset = ($pagina - 1) * $porPagina;

            $consulta = "
                SELECT * FROM {$this->tabla}
                WHERE id_categoria = :idCategoria AND estado = 'aprobado'
                ORDER BY created_at DESC
                LIMIT :limite OFFSET :offset
            ";

            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':idCategoria', $idCategoria, PDO::PARAM_INT);
            $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            \Log::error('Error al obtener libros por categoría: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene libros aprobados solamente
     */
    public function obtenerLibrosAprobados(int $pagina = 1, int $porPagina = 15): array
    {
        try {
            $offset = ($pagina - 1) * $porPagina;

            $consulta = "
                SELECT * FROM {$this->tabla}
                WHERE estado = 'aprobado'
                ORDER BY vistas DESC, created_at DESC
                LIMIT :limite OFFSET :offset
            ";

            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            \Log::error('Error al obtener libros aprobados: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca libros por título o autor
     */
    public function buscarLibros(string $termino, int $pagina = 1, int $porPagina = 15): array
    {
        try {
            $offset = ($pagina - 1) * $porPagina;
            $terminoBuscado = "%{$termino}%";

            $consulta = "
                SELECT * FROM {$this->tabla}
                WHERE (titulo LIKE :termino OR autor LIKE :termino OR descripcion LIKE :termino)
                AND estado = 'aprobado'
                ORDER BY created_at DESC
                LIMIT :limite OFFSET :offset
            ";

            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':termino', $terminoBuscado);
            $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            \Log::error('Error al buscar libros: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea un nuevo libro con validación
     */
    public function crearLibro(array $datos)
    {
        try {
            $camposRequeridos = ['titulo', 'id_categoria', 'id_usuario_subida'];
            if (!$this->validarCamposRequeridos($datos, $camposRequeridos)) {
                return false;
            }

            $datos = $this->sanitizar($datos);

            if (strlen($datos['titulo'] ?? '') < 3) {
                return false;
            }

            $datos['estado'] = $datos['estado'] ?? 'pendiente';
            $datos['vistas'] = 0;
            $datos['created_at'] = date('Y-m-d H:i:s');
            $datos['updated_at'] = date('Y-m-d H:i:s');

            $id = $this->insertar($datos);

            if ($id) {
                return $this->obtenerPorId($id);
            }

            return false;
        } catch (PDOException $e) {
            \Log::error('Error al crear libro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un libro existente
     */
    public function actualizarLibro(int $id, array $datos): bool
    {
        try {
            unset($datos['id_usuario_subida']);
            unset($datos['created_at']);

            $datos = $this->sanitizar($datos);
            $datos['updated_at'] = date('Y-m-d H:i:s');

            return $this->actualizar($id, $datos);
        } catch (PDOException $e) {
            \Log::error('Error al actualizar libro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Incrementa el contador de vistas
     */
    public function incrementarVistas(int $id): bool
    {
        try {
            $consulta = "UPDATE {$this->tabla} SET vistas = vistas + 1 WHERE id = :id";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            \Log::error('Error al incrementar vistas: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Aprueba un libro
     */
    public function aprobarLibro(int $id, int $idValidador, string $comentario = ''): bool
    {
        try {
            $datos = [
                'estado' => 'aprobado',
                'id_validador' => $idValidador,
                'fecha_validacion' => date('Y-m-d H:i:s'),
                'comentario_validacion' => $comentario,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            return $this->actualizar($id, $datos);
        } catch (PDOException $e) {
            \Log::error('Error al aprobar libro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Rechaza un libro
     */
    public function rechazarLibro(int $id, int $idValidador, string $comentario = ''): bool
    {
        try {
            $datos = [
                'estado' => 'rechazado',
                'id_validador' => $idValidador,
                'fecha_validacion' => date('Y-m-d H:i:s'),
                'comentario_validacion' => $comentario,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            return $this->actualizar($id, $datos);
        } catch (PDOException $e) {
            \Log::error('Error al rechazar libro: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene libros pendientes
     */
    public function obtenerLibrosPendientes(): array
    {
        try {
            $consulta = "SELECT * FROM {$this->tabla} WHERE estado = 'pendiente' ORDER BY created_at ASC";

            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            \Log::error('Error al obtener libros pendientes: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Elimina un libro
     */
    public function eliminarLibro(int $id): bool
    {
        try {
            $datos = [
                'estado' => 'eliminado',
                'updated_at' => date('Y-m-d H:i:s')
            ];

            return $this->actualizar($id, $datos);
        } catch (PDOException $e) {
            \Log::error('Error al eliminar libro: ' . $e->getMessage());
            return false;
        }
    }
}
