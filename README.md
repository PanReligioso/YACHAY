# YACHAY - Plataforma Educativa Continental

Plataforma web colaborativa para estudiantes de Ingeniería de Sistemas e Informática de la Universidad Continental, Cusco.

---

## Tabla de Contenidos

- [Características](#características)
- [Requisitos del Sistema](#requisitos-del-sistema)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Uso](#uso)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Tecnologías](#tecnologías)

---

## Características

**Biblioteca Digital**
- Marketplace de libros académicos
- Sistema de validación de contenido
- Búsqueda avanzada por categorías

**Sistema de Apuntes**
- Apuntes organizados por malla curricular
- Soporte para mallas 2018 y 2024
- Filtrado por ciclo y curso

**Grupos de Tutoría**
- Creación de grupos públicos y privados
- Chat en tiempo real
- Gestión de participantes

**Directorio de Restaurantes**
- Mapa interactivo con Google Maps
- Reseñas y calificaciones
- Filtros por precio y ubicación

---

## Requisitos del Sistema

- **PHP** >= 8.0
- **Composer** >= 2.0
- **MySQL/MariaDB** >= 5.7
- **Node.js** >= 14.x (opcional, para assets)
- **Servidor web** Apache/Nginx

---

## Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/yachay.git
cd YACHAY
```

### 2. Navegar a la carpeta Laravel
```bash
cd laravel
```

### 3. Instalar dependencias
```bash
composer install
```

### 4. Configurar el entorno

Copia el archivo de configuración:
```bash
cp .env.example .env
```

### 5. Generar clave de aplicación
```bash
php artisan key:generate
```

---

## Configuración

### Base de Datos

Edita el archivo `.env` con tus credenciales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plataforma_continental
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Importar Base de Datos

Importa el archivo SQL en tu servidor MySQL:
```bash
mysql -u tu_usuario -p plataforma_continental < plataforma_continental.sql
```

O usando phpMyAdmin, importa el archivo `plataforma_continental.sql`

### Configuración de Google OAuth (Opcional)
```env
GOOGLE_CLIENT_ID=tu_client_id
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

## Uso

### Iniciar el servidor de desarrollo

Asegúrate de estar en la carpeta `laravel`:
```bash
php artisan serve
```

El servidor estará disponible en: **http://localhost:8000**

### Rutas Principales

| Módulo | Ruta | Descripción |
|--------|------|-------------|
| Inicio | `/` | Página principal |
| Libros | `/libros` | Biblioteca digital |
| Apuntes | `/apuntes` | Sistema de apuntes |
| Tutorías | `/tutorias` | Grupos de estudio |
| Restaurantes | `/comedores` | Directorio local |

---
### Base de Datos

**1. Iniciar XAMPP**
- Abre XAMPP Control Panel
- Inicia los servicios **Apache** y **MySQL**

**2. Crear la base de datos**

Accede a phpMyAdmin: **http://localhost/phpmyadmin**

Crea una nueva base de datos:
```sql
CREATE DATABASE plataforma_continental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O desde la interfaz de phpMyAdmin:
- Click en "Nueva"
- Nombre: `plataforma_continental`
- Cotejamiento: `utf8mb4_unicode_ci`
- Click en "Crear"

**3. Importar datos**

Importa el archivo SQL en tu base de datos:
- Selecciona la base de datos `plataforma_continental`
- Click en la pestaña "Importar"
- Selecciona el archivo `plataforma_continental.sql`
- Click en "Continuar"

Edita el archivo `.env` con tus credenciales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plataforma_continental
DB_USERNAME=root
DB_PASSWORD=
```

## Estructura del Proyecto
```
YACHAY/
└── laravel/
    ├── app/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   └── Middleware/
    │   └── Models/
    ├── config/
    ├── database/
    │   ├── migrations/
    │   └── seeders/
    ├── public/
    │   ├── css/
    │   └── js/
    ├── resources/
    │   └── views/
    │       ├── layouts/
    │       └── components/
    ├── routes/
    │   └── web.php
    └── .env
```

---

## Tecnologías

### Backend
- Laravel 10.x
- PHP 8.2
- MySQL/MariaDB

### Frontend
- Blade Templates
- CSS3 (Variables + Grid)
- JavaScript Vanilla
- Font Awesome

### Servicios
- Google OAuth 2.0
- Google Maps API
- Google Drive API

---

## Licencia

Este proyecto es de uso educativo para la Universidad Continental.

---

## Autor

Desarrollado por estudiantes de Ingeniería de Sistemas e Informática - Universidad Continental, Cusco.
