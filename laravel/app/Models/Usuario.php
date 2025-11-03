<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /**
     * Nombre de la tabla en la BD
     */
    protected $table = 'usuarios';

    /**
     * Clave primaria personalizada
     */
    protected $primaryKey = 'id_usuario';

    /**
     * Deshabilitar timestamps automáticos de Laravel
     * Porque ya tienes fecha_registro en tu tabla
     */
    public $timestamps = false;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'google_id',
        'email',
        'password',
        'nombre_completo',
        'apellidos',
        'foto_perfil',
        'id_rol',
        'estado',
        'puede_descargar',
        'telefono',
        'universidad',
        'carrera',
        'ciclo',
        'ultimo_acceso'
    ];

    /**
     * Campos ocultos en JSON
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Casting de atributos
     */
    protected $casts = [
        'puede_descargar' => 'boolean',
        'fecha_registro' => 'datetime',
        'ultimo_acceso' => 'datetime',
        'id_rol' => 'integer'
        // ciclo es varchar(50) en BD, se deja como string
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'id_rol' => 3, // Estudiante por defecto
        'estado' => 'activo',
        'puede_descargar' => false,
        'universidad' => 'Universidad Continental',
        'carrera' => 'Ing. de Sistemas e Informática'
    ];

    // ==========================================
    // RELACIONES (comentadas temporalmente)
    // ==========================================
    // Descomentar cuando crees los modelos correspondientes

    /*
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_usuario_subida', 'id_usuario');
    }

    public function apuntes()
    {
        return $this->hasMany(Apunte::class, 'id_usuario_subida', 'id_usuario');
    }

    public function gruposCreados()
    {
        return $this->hasMany(GrupoTutoria::class, 'id_creador', 'id_usuario');
    }

    public function gruposParticipando()
    {
        return $this->belongsToMany(
            GrupoTutoria::class,
            'miembros_grupo',
            'id_usuario',
            'id_grupo'
        )->withPivot('rol_grupo', 'fecha_union');
    }

    public function mensajes()
    {
        return $this->hasMany(MensajeChat::class, 'id_usuario', 'id_usuario');
    }

    public function actividades()
    {
        return $this->hasMany(HistorialActividad::class, 'id_usuario', 'id_usuario');
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class, 'id_usuario_reporta', 'id_usuario');
    }

    public function resenasComedores()
    {
        return $this->hasMany(ResenaComedor::class, 'id_usuario', 'id_usuario');
    }
    */

    // ==========================================
    // MÉTODOS AUXILIARES
    // ==========================================

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdmin()
    {
        return $this->id_rol === 1;
    }

    /**
     * Verificar si el usuario es validador
     */
    public function esValidador()
    {
        return $this->id_rol === 2;
    }

    /**
     * Verificar si el usuario es estudiante
     */
    public function esEstudiante()
    {
        return $this->id_rol === 3;
    }

    /**
     * Verificar si el usuario es docente
     */
    public function esDocente()
    {
        return $this->id_rol === 4;
    }

    /**
     * Verificar si puede descargar contenido
     */
    public function puedeDescargar()
    {
        return $this->puede_descargar === true;
    }

    /**
     * Nombre completo del usuario
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->attributes['nombre_completo'] . ' ' . ($this->apellidos ?? ''));
    }

    /**
     * Avatar o foto de perfil
     */
    public function getAvatarAttribute()
    {
        return $this->foto_perfil ?? 'https://ui-avatars.com/api/?name=' .
               urlencode($this->nombre_completo) . '&background=0284c7&color=fff';
    }

    /**
     * Email corto (sin dominio)
     */
    public function getEmailCortoAttribute()
    {
        return explode('@', $this->email)[0];
    }

    /**
     * Verificar si es email institucional válido
     */
    public function esEmailInstitucional()
    {
        return str_ends_with($this->email, '@continental.edu.pe');
    }

    /**
     * Registrar actividad del usuario
     */
    public function registrarActividad($tipo, $descripcion = null, $ip = null)
    {
        return $this->actividades()->create([
            'tipo_accion' => $tipo,
            'descripcion' => $descripcion,
            'ip_address' => $ip ?? request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Actualizar último acceso
     */
    public function actualizarAcceso()
    {
        $this->update(['ultimo_acceso' => now()]);
    }
}
