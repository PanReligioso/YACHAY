<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LibroServicio;
use App\Database\Conexion;

/**
 * Comando Artisan: libros:import
 *
 * Importa registros desde storage/app/libros.json hacia la tabla `libros` usando LibroServicio.
 * Realiza validaciones básicas y reporta errores y conteos.
 */
class ImportLibrosCommand extends Command
{
    protected $signature = 'libros:import {--dry-run}';
    protected $description = 'Importa storage/app/libros.json a la tabla libros (usa LibroServicio)';

    public function handle()
    {
        $this->info('Iniciando importación de libros (tooling)...');

        $jsonPath = storage_path('app/libros.json');
        if (!file_exists($jsonPath)) {
            $this->error("Archivo no encontrado: {$jsonPath}");
            return 1;
        }

        $raw = file_get_contents($jsonPath);
        $datos = json_decode($raw, true);
        if (!is_array($datos)) {
            $this->error('Formato JSON inválido o vacío');
            return 1;
        }

        // Conectar y validar conexión
        try {
            $pdo = Conexion::obtenerConexion();
        } catch (\Exception $e) {
            $this->error('No se pudo obtener conexión PDO: ' . $e->getMessage());
            return 1;
        }

        $servicio = new LibroServicio();

        $total = 0;
        $importados = 0;
        $skipped = 0;
        $errores = [];

        foreach ($datos as $item) {
            $total++;

            // Validación mínima
            if (empty($item['titulo']) || empty($item['autor'])) {
                $errores[] = "Registro #{$total}: faltan campos requeridos (titulo/autor)";
                continue;
            }

            // Preparar datos acorde a LibroServicio (mapear claves si es necesario)
            $map = [
                'titulo' => $item['titulo'] ?? null,
                'autor' => $item['autor'] ?? null,
                'id_categoria' => $item['categoria_id'] ?? ($item['categoria'] ?? null),
                'portada' => $item['portada'] ?? null,
                'url_drive' => $item['url_drive'] ?? ($item['archivo'] ?? null),
                'descripcion' => $item['descripcion'] ?? ($item['resumen'] ?? null),
                'editorial' => $item['editorial'] ?? null,
                'anio' => $item['anio'] ?? ($item['anio_publicacion'] ?? null),
                'isbn' => $item['isbn'] ?? null,
                'estado' => $item['estado'] ?? 'aprobado',
                'vistas' => $item['vistas'] ?? 0,
                'descargas' => $item['descargas'] ?? 0,
                'id_usuario_subida' => $item['id_usuario_subida'] ?? 1,
            ];

            // Previene duplicados básicos: buscar por titulo+autor
            try {
                $existe = false;
                $consulta = $pdo->prepare('SELECT id FROM libros WHERE titulo = :titulo AND autor = :autor LIMIT 1');
                $consulta->execute([':titulo' => $map['titulo'], ':autor' => $map['autor']]);
                $row = $consulta->fetch();
                if ($row) {
                    $skipped++;
                    continue;
                }

                if ($this->option('dry-run')) {
                    $importados++;
                    continue;
                }

                $resultado = $servicio->crearLibro($map);
                if ($resultado) {
                    $importados++;
                } else {
                    $errores[] = "Registro #{$total}: no se pudo insertar ({$map['titulo']})";
                }
            } catch (\Exception $e) {
                $errores[] = "Registro #{$total}: Excepción - " . $e->getMessage();
            }
        }

        $this->info("Total leídos: {$total}");
        $this->info("Importados: {$importados}");
        $this->info("Omitidos (duplicados): {$skipped}");
        $this->info("Errores: " . count($errores));

        if (!empty($errores)) {
            $this->line('--- Detalle errores ---');
            foreach ($errores as $err) {
                $this->line($err);
            }
        }

        $this->info('Importación finalizada.');
        return 0;
    }
}
