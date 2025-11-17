document.addEventListener('DOMContentLoaded', () => { //ejecuta el codigo cuando el DOM este completamente cargado
    const toggleButton = document.getElementById('theme-toggle'); //obtiene el boton de alternancia
    const htmlElement = document.documentElement; // CRITICO: Targetea el <html> //obtiene el elemento raiz HTML
    const icon = document.getElementById('theme-icon'); //obtiene el icono del boton
    const storageKey = 'yachay-theme-mode'; //clave usada en localStorage

    // --------------------------------------------------
    // A. FUNCION DE APLICACION INMEDIATA (Persistencia)
    // --------------------------------------------------

    function applyTheme() { //funcion para aplicar el tema al cargar
        const currentTheme = localStorage.getItem(storageKey); //obtiene el tema guardado

        // Cargar preferencia guardada
        if (currentTheme === 'dark-mode') { //si el tema es oscuro guardado
            htmlElement.classList.add('dark-mode'); //aplica la clase dark-mode
        } else if (currentTheme === 'light-mode') { //si el tema es claro guardado
            htmlElement.classList.remove('dark-mode'); //asegura que no tenga la clase dark-mode
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) { //si no hay preferencia guardada y el sistema prefiere oscuro
            // Deteccion inicial del sistema operativo
            htmlElement.classList.add('dark-mode'); //aplica oscuro
            localStorage.setItem(storageKey, 'dark-mode'); //guarda la preferencia
        } else {
            // Por defecto, modo claro
            htmlElement.classList.remove('dark-mode'); //aplica claro por defecto
            localStorage.setItem(storageKey, 'light-mode'); //guarda la preferencia
        }

        updateToggleButtonIcon(); //actualiza el icono del boton
    }

    // --------------------------------------------------
    // B. FUNCION DE ALTERNANCIA Y GUARDADO
    // --------------------------------------------------

    function updateToggleButtonIcon() { //funcion para cambiar el icono
        if (icon) { //si el icono existe
            if (htmlElement.classList.contains('dark-mode')) { //si esta en modo oscuro
                icon.classList.remove('fa-moon'); //remueve el icono de luna
                icon.classList.add('fa-sun'); // Cambiar a sol en modo oscuro //agrega el icono de sol
            } else { //si esta en modo claro
                icon.classList.remove('fa-sun'); //remueve el icono de sol
                icon.classList.add('fa-moon'); // Cambiar a luna en modo claro //agrega el icono de luna
            }
        }
    }

    function handleToggleClick() { //funcion que maneja el click del boton
        // Alternar la clase en el <html>
        htmlElement.classList.toggle('dark-mode'); //alterna la clase dark-mode

        if (htmlElement.classList.contains('dark-mode')) { //si ahora esta en modo oscuro
            localStorage.setItem(storageKey, 'dark-mode'); //guarda la preferencia oscura
        } else { //si ahora esta en modo claro
            localStorage.setItem(storageKey, 'light-mode'); //guarda la preferencia clara
        }

        updateToggleButtonIcon(); //actualiza el icono del boton
    }

    // --------------------------------------------------
    // C. INICIALIZACION
    // --------------------------------------------------

    // 1. Aplicar el tema inmediatamente al cargar el DOM
    applyTheme(); //aplica el tema al inicio

    // 2. Asignar el listener al boton
    if (toggleButton) { //si el boton existe
        toggleButton.addEventListener('click', handleToggleClick); //asigna el evento click
    }
});
