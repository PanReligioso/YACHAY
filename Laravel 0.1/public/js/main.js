/* ==========================================
   JAVASCRIPT PRINCIPAL - YACHAY
   ========================================== */

// Variables globales
const header = document.getElementById('header'); //obtiene el elemento header
const menuToggle = document.getElementById('menuToggle'); //obtiene el boton de menu movil
const navMenu = document.getElementById('navMenu'); //obtiene el menu de navegacion
const scrollTopBtn = document.getElementById('scrollTop'); //obtiene el boton para ir arriba

// ==========================================
// HEADER SCROLL EFFECT
// ==========================================
window.addEventListener('scroll', () => { //maneja el evento scroll de la ventana
  if (window.scrollY > 50) { //si el scroll es mayor a 50px
    header.classList.add('scrolled'); //agrega clase 'scrolled' al header
  } else {
    header.classList.remove('scrolled'); //remueve clase 'scrolled'
  }

  // Show/hide scroll to top button
  if (window.scrollY > 300) { //si el scroll es mayor a 300px
    scrollTopBtn.classList.add('show'); //muestra el boton de scroll a top
  } else {
    scrollTopBtn.classList.remove('show'); //oculta el boton de scroll a top
  }
});

// ==========================================
// MOBILE MENU TOGGLE
// ==========================================
if (menuToggle && navMenu) { //si los elementos del menu existen
  menuToggle.addEventListener('click', () => { //maneja el click en el boton de menu
    menuToggle.classList.toggle('active'); //alterna la clase 'active' en el boton
    navMenu.classList.toggle('active'); //alterna la clase 'active' en el menu

    // Prevent body scroll when menu is open
    if (navMenu.classList.contains('active')) { //si el menu esta abierto
      document.body.style.overflow = 'hidden'; //previene el scroll en el body
    } else {
      document.body.style.overflow = ''; //restaura el scroll
    }
  });

  // Close menu when clicking outside
  document.addEventListener('click', (e) => { //maneja el click en todo el documento
    if (!navMenu.contains(e.target) && !menuToggle.contains(e.target)) { //si el click no fue en el menu ni en el boton
      menuToggle.classList.remove('active'); //cierra el menu y el boton
      navMenu.classList.remove('active');
      document.body.style.overflow = ''; //restaura el scroll
    }
  });

  // Close menu on link click (mobile)
  const navLinks = navMenu.querySelectorAll('.nav-link'); //obtiene todos los enlaces
  navLinks.forEach(link => { //itera sobre los enlaces
    link.addEventListener('click', () => { //maneja el click en el enlace
      if (window.innerWidth <= 768) { //si es un dispositivo movil
        menuToggle.classList.remove('active'); //cierra el menu
        navMenu.classList.remove('active');
        document.body.style.overflow = ''; //restaura el scroll
      }
    });
  });
}

// ==========================================
// SCROLL TO TOP
// ==========================================
if (scrollTopBtn) { //si el boton existe
  scrollTopBtn.addEventListener('click', () => { //maneja el click en el boton
    window.scrollTo({ //desplaza la ventana a la posicion superior
      top: 0,
      behavior: 'smooth' //con desplazamiento suave
    });
  });
}

// ==========================================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ==========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => { //selecciona todos los enlaces de ancla
  anchor.addEventListener('click', function (e) { //maneja el click
    const href = this.getAttribute('href'); //obtiene el atributo href

    // Skip if href is just "#"
    if (href === '#') return; //ignora si es solo almohadilla

    const target = document.querySelector(href); //obtiene el elemento destino
    if (target) {
      e.preventDefault(); //previene la accion por defecto
      const headerHeight = header.offsetHeight; //obtiene la altura del header
      const targetPosition = target.offsetTop - headerHeight - 20; //calcula la posicion de destino con offset

      window.scrollTo({ //desplaza la ventana a la posicion
        top: targetPosition,
        behavior: 'smooth' //con desplazamiento suave
      });
    }
  });
});

// ==========================================
// INTERSECTION OBSERVER FOR ANIMATIONS
// ==========================================
const observerOptions = { //opciones del IntersectionObserver
  threshold: 0.1, //ejecuta cuando el 10% del elemento es visible
  rootMargin: '0px 0px -50px 0px' //margen de la raiz para disparar antes
};

const observer = new IntersectionObserver((entries) => { //crea el observador
  entries.forEach(entry => { //itera sobre las entradas
    if (entry.isIntersecting) { //si el elemento es visible
      entry.target.classList.add('fade-in'); //agrega la clase de animacion
      observer.unobserve(entry.target); //deja de observar el elemento
    }
  });
}, observerOptions);

// Observe cards and sections
document.querySelectorAll('.card, .feature-card, .section').forEach(el => { //selecciona elementos para observar
  observer.observe(el); //empieza a observar cada elemento
});

// ==========================================
// FORM VALIDATION (Para formularios futuros)
// ==========================================
const forms = document.querySelectorAll('form'); //obtiene todos los formularios
forms.forEach(form => { //itera sobre los formularios
  form.addEventListener('submit', function(e) { //maneja el evento submit
    const requiredFields = this.querySelectorAll('[required]'); //obtiene campos requeridos
    let isValid = true; //bandera de validacion

    requiredFields.forEach(field => { //itera sobre campos requeridos
      if (!field.value.trim()) { //si el campo esta vacio
        isValid = false; //invalida el formulario
        field.classList.add('error'); //agrega clase de error

        // Remove error class on input
        field.addEventListener('input', function() { //remueve la clase de error al escribir
          this.classList.remove('error');
        }, { once: true });
      }
    });

    if (!isValid) { //si el formulario no es valido
      e.preventDefault(); //previene el envio
      alert('Por favor, completa todos los campos requeridos.'); //muestra alerta
    }
  });
});

// ==========================================
// RESPONSIVE RESIZE HANDLER
// ==========================================
let resizeTimer; //variable para el temporizador de redimension
window.addEventListener('resize', () => { //maneja el evento resize
  clearTimeout(resizeTimer); //limpia el temporizador anterior
  resizeTimer = setTimeout(() => { //establece un nuevo temporizador
    // Close mobile menu on resize to desktop
    if (window.innerWidth > 768) { //si la ventana es mas grande que movil
      menuToggle.classList.remove('active'); //cierra el menu movil
      navMenu.classList.remove('active');
      document.body.style.overflow = ''; //restaura el scroll
    }
  }, 250); //espera 250ms antes de ejecutar
});

// ==========================================
// LOADING ANIMATION
// ==========================================
window.addEventListener('load', () => { //maneja el evento load de la ventana
  document.body.classList.add('loaded'); //agrega clase 'loaded' al body
});

// ==========================================
// UTILITY FUNCTIONS
// ==========================================

// Show toast notification
function showToast(message, type = 'info') { //funcion para mostrar notificaciones toast
  const toast = document.createElement('div'); //crea un nuevo div
  toast.className = `toast toast-${type}`; //asigna clases css
  toast.textContent = message; //asigna el mensaje
  toast.style.cssText = `
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#0284c7'};
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    animation: slideIn 0.3s ease-out;
  `; //aplica estilos CSS directamente

  document.body.appendChild(toast); //agrega el toast al body

  setTimeout(() => { //temporizador para ocultar el toast
    toast.style.animation = 'slideOut 0.3s ease-out'; //inicia animacion de salida
    setTimeout(() => toast.remove(), 300); //remueve el elemento despues de la animacion
  }, 3000); //permanece visible por 3 segundos
}

// Format date
function formatDate(date) { //funcion para formatear la fecha
  const options = { year: 'numeric', month: 'long', day: 'numeric' }; //opciones de formato
  return new Date(date).toLocaleDateString('es-ES', options); //formatea la fecha a español
}

// Debounce function
function debounce(func, wait) { //funcion de debounce para limitar llamadas a una funcion
  let timeout; //variable para el temporizador
  return function executedFunction(...args) { //retorna la funcion debounced
    const later = () => { //funcion a ejecutar despues del tiempo de espera
      clearTimeout(timeout); //limpia el timeout
      func(...args); //ejecuta la funcion original
    };
    clearTimeout(timeout); //limpia el timeout al inicio de cada llamada
    timeout = setTimeout(later, wait); //establece un nuevo timeout
  };
}

// Console welcome message
console.log('%c¡Bienvenido a YACHAY! 🎓', 'color: #0284c7; font-size: 24px; font-weight: bold;'); //mensaje de bienvenida en consola
console.log('%cPlataforma educativa para estudiantes de Ingenieria de Sistemas', 'color: #64748b; font-size: 14px;'); //subtitulo en consola
console.log('%cHecho con ❤️ por estudiantes para estudiantes', 'color: #9333ea; font-size: 12px;'); //mensaje final en consola
