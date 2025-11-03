# YACHAY
YACHAY es una plataforma web educativa diseñada para estudiantes de Ingeniería de Sistemas en la Universidad Continental Cusco, enfocada en compartir recursos, organizar tutorías y facilitar servicios útiles a la comunidad estudiantil.

El proyecto ha sido refactorizado y migrado a una estructura moderna para facilitar el desarrollo, manteniendo el principio del patrón MVC (Modelo-Vista-Controlador).

Framework Base: Laravel (Utilizado principalmente por Blade y sus helpers).

Lógica de Negocio: PHP Puro (Directamente en las vistas Blade).

Datos Temporales: Archivos JSON (storage/app/) — Se utiliza como la fuente de datos temporal, simulando la capa del Modelo y la Base de Datos.

Frontend: HTML5, CSS (Variables CSS/Responsivo), JavaScript (ES6).

Esta versión de la plataforma incluye la implementación visual completa y las funcionalidades clave del frontend:

1. 🗺️ Módulo de Comedores (Focus)
Centrado Preciso: Mapa inicial centrado automáticamente en las coordenadas exactas de la Universidad Continental Cusco.

Localización: Directorio completo de comedores, mostrando cada establecimiento como un marcador interactivo en Google Maps (initMap).

Filtros: Funcionalidad completa de listado y filtrado por universidad, precio y tipo de comida.

2. 🌗 Personalización y Diseño
Modo Oscuro Persistente: Se agregó un toggle de modo oscuro que recuerda la preferencia del usuario en todas las páginas utilizando JavaScript y localStorage.

Diseño Responsivo: Implementación completa de un diseño adaptativo (Responsive Design) basado en variables CSS.

3. 🔑 Seguridad y Estructura
Autenticación Base: Estructura para el manejo de sesiones y lógica de autenticación (Login/Registro).

Seguridad de Credenciales: La configuración de las API Keys sensibles (Google Maps, Google OAuth) ha sido movida y asegurada mediante el uso de variables de entorno (.env).

🚀 Instalación y Uso (Desarrollo)
Clona el repositorio.

Ejecuta composer install.

Copia .env.example a .env y configura las variables, incluyendo:

Fragmento de código

APP_URL=http://localhost
GOOGLE_MAPS_KEY="[Tu clave de Maps aquí]"
Ejecuta el servidor de desarrollo: php artisan serve.

Accede a las rutas, por ejemplo: /comedores.