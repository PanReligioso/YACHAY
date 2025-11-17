<?php

namespace App\Utils;

/**
 * LoggerUtil - Gestión centralizada de logs
 * 
 * Proporciona métodos para registrar diferentes tipos de eventos.
 * Los logs se guardan en storage/logs/
 * 
 * @author Equipo de Desarrollo
 * @version 1.0.0
 */
class LoggerUtil
{
    const NIVEL_INFO = 'INFO';
    const NIVEL_ERROR = 'ERROR';
    const NIVEL_WARNING = 'WARNING';
    const NIVEL_DEBUG = 'DEBUG';
    const NIVEL_CRITICO = 'CRITICO';

    private static string $directorioLogs = '';

    /**
     * Inicializa el directorio de logs
     */
    public static function inicializar(): void
    {
        self::$directorioLogs = storage_path('logs');

        if (!is_dir(self::$directorioLogs)) {
            mkdir(self::$directorioLogs, 0755, true);
        }
    }

    /**
     * Registra un evento de información
     */
    public static function info(string $mensaje, array $contexto = []): void
    {
        self::registrar(self::NIVEL_INFO, $mensaje, $contexto);
    }

    /**
     * Registra un error
     */
    public static function error(string $mensaje, array $contexto = []): void
    {
        self::registrar(self::NIVEL_ERROR, $mensaje, $contexto);
    }

    /**
     * Registra una advertencia
     */
    public static function warning(string $mensaje, array $contexto = []): void
    {
        self::registrar(self::NIVEL_WARNING, $mensaje, $contexto);
    }

    /**
     * Registra información de debug
     */
    public static function debug(string $mensaje, array $contexto = []): void
    {
        self::registrar(self::NIVEL_DEBUG, $mensaje, $contexto);
    }

    /**
     * Registra un evento crítico
     */
    public static function critico(string $mensaje, array $contexto = []): void
    {
        self::registrar(self::NIVEL_CRITICO, $mensaje, $contexto);
    }

    /**
     * Método privado para registrar en archivo
     */
    private static function registrar(string $nivel, string $mensaje, array $contexto = []): void
    {
        self::inicializar();

        $fecha = date('Y-m-d H:i:s');
        $archivoLog = self::$directorioLogs . '/aplicacion.log';

        $contextoFormato = '';
        if (!empty($contexto)) {
            $contextoFormato = ' | Contexto: ' . json_encode($contexto);
        }

        $linea = "[{$fecha}] [{$nivel}] {$mensaje}{$contextoFormato}" . PHP_EOL;

        file_put_contents($archivoLog, $linea, FILE_APPEND | LOCK_EX);
    }

    /**
     * Obtiene los últimos logs
     */
    public static function obtenerUltimos(int $cantidad = 50): array
    {
        self::inicializar();

        $archivoLog = self::$directorioLogs . '/aplicacion.log';

        if (!file_exists($archivoLog)) {
            return [];
        }

        $lineas = file($archivoLog);
        $ultimas = array_slice($lineas, -$cantidad);

        return array_map('trim', $ultimas);
    }

    /**
     * Limpia los logs antiguos
     */
    public static function limpiarAntiguos(int $diasRetener = 30): void
    {
        self::inicializar();

        $archivoLog = self::$directorioLogs . '/aplicacion.log';

        if (!file_exists($archivoLog)) {
            return;
        }

        $fechaLimite = strtotime("-{$diasRetener} days");
        $tiempoArchivo = filemtime($archivoLog);

        if ($tiempoArchivo < $fechaLimite) {
            unlink($archivoLog);
        }
    }
}
