// tutorias-crear.js

function actualizarTipo(tipo) { //funcion para actualizar la visibilidad y el badge segun el tipo
    const codigoDiv = document.getElementById('codigoDiv'); //obtiene el contenedor del codigo
    const badge = document.getElementById('preview-badge'); //obtiene el badge de previsualizacion

    if (tipo === 'privado') { //si el grupo es privado
        codigoDiv.style.display = 'block'; //muestra el campo de codigo
        document.getElementById('codigo').required = true; //hace el campo codigo requerido
        badge.innerHTML = '<i class="fas fa-lock"></i> Privado'; //actualiza el badge a privado
        badge.style.background = 'var(--accent-orange)'; //color naranja
    } else { //si el grupo es publico
        codigoDiv.style.display = 'none'; //oculta el campo de codigo
        document.getElementById('codigo').required = false; //el campo codigo ya no es requerido
        badge.innerHTML = '<i class="fas fa-globe"></i> Publico'; //actualiza el badge a publico
        badge.style.background = 'var(--accent-green)'; //color verde
    }
}

function actualizarPreview() { //funcion para actualizar la previsualizacion del grupo
    const nombre = document.getElementById('nombre').value || 'Nombre del grupo...'; //obtiene el nombre o texto por defecto
    const descripcion = document.getElementById('descripcion').value || 'Descripcion del grupo...'; //obtiene la descripcion o texto por defecto
    const max = document.getElementById('max').value || '50'; //obtiene el maximo de participantes
    const cursoSelect = document.getElementById('curso'); //obtiene el selector de curso
    const cursoTexto = cursoSelect.options[cursoSelect.selectedIndex].text; //obtiene el nombre del curso seleccionado

    // Actualizar preview
    document.getElementById('preview-nombre').textContent = nombre; //actualiza el nombre en la previsualizacion
    document.getElementById('preview-nombre').style.color = nombre === 'Nombre del grupo...' ? 'var(--text-tertiary)' : 'var(--text-primary)'; //cambia color si es por defecto

    document.getElementById('preview-descripcion').textContent = descripcion; //actualiza la descripcion
    document.getElementById('preview-descripcion').style.color = descripcion === 'Descripcion del grupo...' ? 'var(--text-tertiary)' : 'var(--text-secondary)'; //cambia color si es por defecto

    document.getElementById('preview-max').textContent = `0 / ${max} participantes`; //actualiza el maximo de participantes

    const cursoDiv = document.getElementById('preview-curso-div'); //obtiene el contenedor del curso
    if (cursoSelect.value) { //si se selecciono un curso
        cursoDiv.style.display = 'flex'; //muestra el curso
        document.getElementById('preview-curso').textContent = cursoTexto; //muestra el nombre del curso
    } else {
        cursoDiv.style.display = 'none'; //oculta el curso
    }
}

// Contador de caracteres para descripcion
const descripcionTextarea = document.getElementById('descripcion'); //obtiene el textarea de descripcion
if (descripcionTextarea) { //si el textarea existe
    const maxLength = 1000; //maximo de caracteres
    const counterDiv = document.createElement('div'); //crea el div para el contador
    counterDiv.style.cssText = 'text-align: right; font-size: var(--text-sm); color: var(--text-tertiary); margin-top: var(--spacing-xs);'; //estilos del contador
    counterDiv.innerHTML = `<span id="charCount">0</span> / ${maxLength} caracteres`; //contenido inicial del contador
    descripcionTextarea.parentNode.appendChild(counterDiv); //agrega el contador al DOM

    descripcionTextarea.addEventListener('input', function() { //maneja el evento input
        const count = this.value.length; //obtiene el conteo de caracteres
        document.getElementById('charCount').textContent = count; //actualiza el conteo en el DOM

        if (count > maxLength) { //si supera el limite
            counterDiv.style.color = 'var(--accent-red)'; //color rojo
            this.value = this.value.substring(0, maxLength); //trunca el texto
        } else if (count > maxLength * 0.9) { //si esta cerca del limite
            counterDiv.style.color = 'var(--accent-orange)'; //color naranja
        } else {
            counterDiv.style.color = 'var(--text-tertiary)'; //color normal
        }
    });
}

// Generar codigo aleatorio
function generarCodigo() { //funcion para generar un codigo aleatorio
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; //caracteres posibles
    let codigo = ''; //inicializa el codigo
    for (let i = 0; i < 8; i++) { //itera 8 veces
        codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length)); //agrega un caracter aleatorio
    }
    document.getElementById('codigo').value = codigo; //asigna el codigo al campo de entrada
    actualizarPreview(); //actualiza la previsualizacion
}

// Agregar boton de generar codigo
const codigoInput = document.getElementById('codigo'); //obtiene el campo de codigo
if (codigoInput) { //si el campo existe
    const generarBtn = document.createElement('button'); //crea el boton
    generarBtn.type = 'button'; //tipo boton
    generarBtn.className = 'btn btn-secondary'; //clases css
    generarBtn.innerHTML = '<i class="fas fa-random"></i> Generar'; //texto e icono
    generarBtn.style.cssText = 'margin-top: var(--spacing-sm);'; //estilos
    generarBtn.onclick = generarCodigo; //asigna la funcion generarCodigo al click
    codigoInput.parentNode.appendChild(generarBtn); //agrega el boton al DOM
}

// Validacion en tiempo real
document.getElementById('nombre')?.addEventListener('blur', function() { //maneja el evento blur del nombre
    if (this.value.trim().length < 5) { //si el nombre es muy corto
        this.style.borderColor = 'var(--accent-red)'; //resalta el borde en rojo
        showToast('El nombre debe tener al menos 5 caracteres', 'error'); //muestra un toast de error
    } else {
        this.style.borderColor = 'var(--primary-200)'; //color de borde normal
    }
});

document.getElementById('descripcion')?.addEventListener('blur', function() { //maneja el evento blur de la descripcion
    if (this.value.trim().length < 20) { //si la descripcion es muy corta
        this.style.borderColor = 'var(--accent-red)'; //resalta el borde en rojo
        showToast('La descripcion debe tener al menos 20 caracteres', 'error'); //muestra un toast de error
    } else {
        this.style.borderColor = 'var(--primary-200)'; //color de borde normal
    }
});

// Confirmar antes de salir si hay cambios
let formModificado = false; //bandera para detectar si el formulario fue modificado
document.querySelectorAll('#nombre, #descripcion, #curso, input[name="tipo"], #max, #codigo').forEach(input => { //selecciona campos
    input.addEventListener('input', () => formModificado = true); //marca como modificado en cualquier input
});

window.addEventListener('beforeunload', (e) => { //maneja el evento antes de cerrar la ventana
    if (formModificado) { //si el formulario fue modificado
        e.preventDefault(); //previene la accion por defecto
        e.returnValue = ''; //muestra el mensaje de advertencia del navegador
    }
});

// No mostrar alerta al enviar formulario
document.querySelector('form')?.addEventListener('submit', () => { //maneja el evento submit del formulario
    formModificado = false; //restablece la bandera para no mostrar la alerta al enviar
});
