<?php

namespace App\Database;

use PDO;
use PDOException;

/**
 * Clase Conexion - Gestiona la conexión a la base de datos MySQL
 * 
 * Proporciona una única conexión PDO para toda la aplicación.
 * Implementa prepared statements para prevenir SQL Injection.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0.0
 */
class Conexion
{
    /**
     * @var PDO|null Instancia única de la conexión
     */
    private static ?PDO $conexion = null;

    /**
     * Constructor privado para implementar patrón Singleton
     */
    private function __construct()
    {
    }

    /**
     * Obtiene la conexión a la base de datos (Singleton)
     * 
     * @return PDO La instancia de conexión PDO
     * @throws PDOException Si falla la conexión
     */
    public static function obtenerConexion(): PDO
    {
        if (self::$conexion === null) {
            try {
                $host = env('DB_HOST', '127.0.0.1');
                $puerto = env('DB_PORT', 3306);
                $baseDatos = env('DB_DATABASE', 'laravel');
                $usuario = env('DB_USERNAME', 'root');
                $contrasena = env('DB_PASSWORD', '');

                $dsn = "mysql:host={$host};port={$puerto};dbname={$baseDatos};charset=utf8mb4";

                self::$conexion = new PDO(
                    $dsn,
                    $usuario,
                    $contrasena,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => false,
                    ]
                );

                // Establecer timezone
                self::$conexion->exec("SET time_zone = '+00:00'");
            } catch (PDOException $e) {
                \Log::error('Error de conexión a base de datos: ' . $e->getMessage());
                throw new PDOException('No se pudo conectar a la base de datos');
            }
        }

        return self::$conexion;
    }

    /**
     * Cierra la conexión
     */
    public static function cerrar(): void
    {
        self::$conexion = null;
    }
}
