document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement; // CRÍTICO: Targetea el <html>
    const icon = document.getElementById('theme-icon');
    const storageKey = 'yachay-theme-mode';

    // --------------------------------------------------
    // A. FUNCIÓN DE APLICACIÓN INMEDIATA (Persistencia)
    // --------------------------------------------------

    function applyTheme() {
        const currentTheme = localStorage.getItem(storageKey);

        // Cargar preferencia guardada
        if (currentTheme === 'dark-mode') {
            htmlElement.classList.add('dark-mode');
        } else if (currentTheme === 'light-mode') {
            htmlElement.classList.remove('dark-mode');
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Detección inicial del sistema operativo
            htmlElement.classList.add('dark-mode');
            localStorage.setItem(storageKey, 'dark-mode');
        } else {
            // Por defecto, modo claro
            htmlElement.classList.remove('dark-mode');
            localStorage.setItem(storageKey, 'light-mode');
        }

        updateToggleButtonIcon();
    }

    // --------------------------------------------------
    // B. FUNCIÓN DE ALTERNANCIA Y GUARDADO
    // --------------------------------------------------

    function updateToggleButtonIcon() {
        if (icon) {
            if (htmlElement.classList.contains('dark-mode')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun'); // Cambiar a sol en modo oscuro
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon'); // Cambiar a luna en modo claro
            }
        }
    }

    function handleToggleClick() {
        // Alternar la clase en el <html>
        htmlElement.classList.toggle('dark-mode');

        if (htmlElement.classList.contains('dark-mode')) {
            localStorage.setItem(storageKey, 'dark-mode');
        } else {
            localStorage.setItem(storageKey, 'light-mode');
        }

        updateToggleButtonIcon();
    }

    // --------------------------------------------------
    // C. INICIALIZACIÓN
    // --------------------------------------------------

    // 1. Aplicar el tema inmediatamente al cargar el DOM
    applyTheme();

    // 2. Asignar el listener al botón
    if (toggleButton) {
        toggleButton.addEventListener('click', handleToggleClick);
    }
});
