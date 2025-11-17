<?php

namespace App\Services;

use PDO;
use PDOException;
use App\Database\Conexion;

/**
 * Clase ModeloBase - Base para todos los servicios de datos
 * 
 * Proporciona métodos comunes para interactuar con la base de datos.
 * Implementa consultas preparadas y sanitización de datos.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0.0
 */
abstract class ModeloBase
{
    /**
     * @var PDO Conexión a la base de datos
     */
    protected PDO $conexion;

    /**
     * @var string Nombre de la tabla
     */
    protected string $tabla;

    /**
     * @var string Clave primaria de la tabla
     */
    protected string $clavePrimaria = 'id';

    /**
     * Constructor del modelo base
     */
    public function __construct()
    {
        $this->conexion = Conexion::obtenerConexion();
    }

    /**
     * Obtiene todos los registros de la tabla
     * 
     * @param int $pagina Número de página (opcional)
     * @param int $porPagina Registros por página (opcional)
     * @return array Array de registros
     */
    public function obtenerTodos(int $pagina = 1, int $porPagina = 15): array
    {
        try {
            $offset = ($pagina - 1) * $porPagina;

            $consulta = "SELECT * FROM {$this->tabla} LIMIT :limite OFFSET :offset";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            \Log::error("Error al obtener todos de {$this->tabla}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un registro por su clave primaria
     * 
     * @param mixed $id Valor de la clave primaria
     * @return array|null Registro o null si no existe
     */
    public function obtenerPorId($id): ?array
    {
        try {
            $consulta = "SELECT * FROM {$this->tabla} WHERE {$this->clavePrimaria} = :id LIMIT 1";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $resultado = $stmt->fetch();
            return $resultado ?: null;
        } catch (PDOException $e) {
            \Log::error("Error al obtener por ID en {$this->tabla}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Inserta un nuevo registro
     * 
     * @param array $datos Array con los datos a insertar
     * @return mixed ID del registro insertado o false en error
     */
    public function insertar(array $datos)
    {
        try {
            $datos = $this->sanitizar($datos);
            $columnas = implode(', ', array_keys($datos));
            $placeholders = ':' . implode(', :', array_keys($datos));

            $consulta = "INSERT INTO {$this->tabla} ({$columnas}) VALUES ({$placeholders})";
            $stmt = $this->conexion->prepare($consulta);

            foreach ($datos as $clave => $valor) {
                $stmt->bindValue(":{$clave}", $valor);
            }

            if ($stmt->execute()) {
                return $this->conexion->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            \Log::error("Error al insertar en {$this->tabla}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un registro
     * 
     * @param mixed $id Valor de la clave primaria
     * @param array $datos Array con los datos a actualizar
     * @return bool True si se actualizó correctamente
     */
    public function actualizar($id, array $datos): bool
    {
        try {
            $datos = $this->sanitizar($datos);
            $actualizaciones = [];

            foreach ($datos as $clave => $valor) {
                $actualizaciones[] = "{$clave} = :{$clave}";
            }

            $set = implode(', ', $actualizaciones);
            $consulta = "UPDATE {$this->tabla} SET {$set} WHERE {$this->clavePrimaria} = :id";
            $stmt = $this->conexion->prepare($consulta);

            foreach ($datos as $clave => $valor) {
                $stmt->bindValue(":{$clave}", $valor);
            }
            $stmt->bindValue(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            \Log::error("Error al actualizar en {$this->tabla}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un registro
     * 
     * @param mixed $id Valor de la clave primaria
     * @return bool True si se eliminó correctamente
     */
    public function eliminar($id): bool
    {
        try {
            $consulta = "DELETE FROM {$this->tabla} WHERE {$this->clavePrimaria} = :id";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            \Log::error("Error al eliminar de {$this->tabla}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el total de registros
     * 
     * @return int Total de registros
     */
    public function obtenerTotal(): int
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM {$this->tabla}";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute();
            $resultado = $stmt->fetch();
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            \Log::error("Error al contar en {$this->tabla}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Busca registros por un campo específico
     * 
     * @param string $campo Campo donde buscar
     * @param mixed $valor Valor a buscar
     * @param string $operador Operador SQL (=, LIKE, >, <, etc)
     * @return array Array de registros encontrados
     */
    public function buscar(string $campo, $valor, string $operador = '='): array
    {
        try {
            if ($operador === 'LIKE') {
                $valor = "%{$valor}%";
            }

            $consulta = "SELECT * FROM {$this->tabla} WHERE {$campo} {$operador} :valor";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->bindValue(':valor', $valor);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            \Log::error("Error al buscar en {$this->tabla}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Sanitiza los datos para prevenir SQL Injection
     * 
     * @param array $datos Datos a sanitizar
     * @return array Datos sanitizados
     */
    protected function sanitizar(array $datos): array
    {
        $sanitizados = [];

        foreach ($datos as $clave => $valor) {
            if (is_string($valor)) {
                $valor = trim($valor);
                $valor = strip_tags($valor);
                $valor = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
            }
            $sanitizados[$clave] = $valor;
        }

        return $sanitizados;
    }

    /**
     * Valida que un array tenga campos requeridos
     * 
     * @param array $datos Datos a validar
     * @param array $camposRequeridos Campos que deben estar presentes
     * @return bool True si son válidos
     */
    protected function validarCamposRequeridos(array $datos, array $camposRequeridos): bool
    {
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo]) || ($datos[$campo] === '' && !is_numeric($datos[$campo]))) {
                return false;
            }
        }
        return true;
    }
}
