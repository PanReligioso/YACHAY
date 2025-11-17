<?php

namespace App\Commands;

use PDO;
use Exception;

/**
 * MigracionDatos - Script para migrar datos de JSON a MySQL
 * 
 * ANTES DE USAR:
 * 1. Hacer backup de la BD
 * 2. Colocar archivo JSON en storage/migration/
 * 3. Ejecutar: php artisan migrate:json-a-mysql
 * 
 * @author Equipo de Desarrollo
 * @version 1.0.0
 */
class MigracionDatos
{
    private PDO $conexion;
    private array $errores = [];
    private int $insertados = 0;
    private int $saltados = 0;

    public function __construct()
    {
        $this->conexion = \App\Database\Conexion::obtenerConexion();
    }

    /**
     * Migra libros desde JSON a MySQL
     */
    public function migrarLibros(string $rutaJSON): bool
    {
        try {
            if (!file_exists($rutaJSON)) {
                throw new Exception("Archivo JSON no encontrado: {$rutaJSON}");
            }

            $contenido = file_get_contents($rutaJSON);
            $libros = json_decode($contenido, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON inválido: ' . json_last_error_msg());
            }

            if (!is_array($libros)) {
                throw new Exception('El JSON debe contener un array de libros');
            }

            \Log::info("Iniciando migración de " . count($libros) . " libros");

            foreach ($libros as $indice => $libro) {
                try {
                    if (!$this->validarLibro($libro)) {
                        $this->saltados++;
                        \Log::warning("Libro #{$indice} saltado por validación inválida");
                        continue;
                    }

                    if ($this->insertarLibro($libro)) {
                        $this->insertados++;
                    } else {
                        $this->saltados++;
                    }
                } catch (Exception $e) {
                    $this->saltados++;
                    $this->errores[] = "Libro #{$indice}: " . $e->getMessage();
                    \Log::error("Error al procesar libro #{$indice}: " . $e->getMessage());
                }
            }

            $this->mostrarReporte();
            return $this->saltados === 0;
        } catch (Exception $e) {
            \Log::error("Error fatal en migración: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida que un libro tenga los campos requeridos
     */
    private function validarLibro(array $libro): bool
    {
        $requeridos = ['titulo', 'archivo_url'];

        foreach ($requeridos as $campo) {
            if (empty($libro[$campo])) {
                return false;
            }
        }

        // Validar que no sea registro duplicado (por ISBN o título+autor)
        if (!empty($libro['isbn'])) {
            if ($this->existeISBN($libro['isbn'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica si un ISBN ya existe
     */
    private function existeISBN(string $isbn): bool
    {
        $stmt = $this->conexion->prepare('SELECT COUNT(*) as total FROM libros WHERE isbn = ?');
        $stmt->execute([$isbn]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;
    }

    /**
     * Inserta un libro en la BD
     */
    private function insertarLibro(array $libro): bool
    {
        try {
            $datos = [
                'titulo' => $libro['titulo'] ?? null,
                'autor' => $libro['autor'] ?? null,
                'descripcion' => $libro['descripcion'] ?? null,
                'editorial' => $libro['editorial'] ?? null,
                'anio_publicacion' => $libro['anio_publicacion'] ?? null,
                'isbn' => $libro['isbn'] ?? null,
                'archivo_url' => $libro['archivo_url'],
                'id_categoria' => $libro['id_categoria'] ?? 1,
                'id_usuario_subida' => $libro['id_usuario_subida'] ?? 1,
                'estado' => 'aprobado',
                'vistas' => $libro['vistas'] ?? 0,
                'descargas' => $libro['descargas'] ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $columnas = implode(', ', array_keys($datos));
            $placeholders = implode(', ', array_fill(0, count($datos), '?'));

            $sql = "INSERT INTO libros ({$columnas}) VALUES ({$placeholders})";
            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute(array_values($datos));
        } catch (Exception $e) {
            throw new Exception("Error al insertar libro: " . $e->getMessage());
        }
    }

    /**
     * Muestra un reporte de la migración
     */
    private function mostrarReporte(): void
    {
        $total = $this->insertados + $this->saltados;

        echo "\n========================================\n";
        echo "REPORTE DE MIGRACIÓN DE LIBROS\n";
        echo "========================================\n";
        echo "Total procesado: {$total}\n";
        echo "Insertados exitosamente: {$this->insertados}\n";
        echo "Saltados/Errores: {$this->saltados}\n";

        if (!empty($this->errores)) {
            echo "\nErrores encontrados:\n";
            foreach ($this->errores as $error) {
                echo "  - {$error}\n";
            }
        }

        echo "========================================\n";
    }
}
