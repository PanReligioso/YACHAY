// tutorias-show.js

function mostrarModalCodigo() { //funcion para mostrar el modal de codigo
    const modal = document.getElementById('modalCodigo'); //obtiene el elemento modal
    modal.style.display = 'flex'; //muestra el modal usando display flex
}

function cerrarModal() { //funcion para cerrar el modal de codigo
    const modal = document.getElementById('modalCodigo'); //obtiene el elemento modal
    modal.style.display = 'none'; //oculta el modal
    document.getElementById('codigoInput').value = ''; //limpia el campo de entrada de codigo
}

function verificarCodigo(grupoId, codigoCorrecto) { //funcion para verificar el codigo de acceso
    const codigoIngresado = document.getElementById('codigoInput').value.trim(); //obtiene el codigo ingresado

    if (codigoIngresado === '') { //si el campo esta vacio
        alert('Por favor ingresa el codigo de acceso'); //alerta de campo vacio
        return; //sale de la funcion
    }

    if (codigoIngresado === codigoCorrecto) { //si el codigo ingresado es el correcto
        unirseGrupo(grupoId); //llama a la funcion para unirse
        cerrarModal(); //cierra el modal
    } else { //si el codigo es incorrecto
        alert('Codigo incorrecto. Verifica con el creador del grupo.'); //alerta de codigo incorrecto
        document.getElementById('codigoInput').value = ''; //limpia el campo de entrada
    }
}

function unirseGrupo(grupoId) { //funcion para unirse a un grupo
    if (confirm('¿Deseas unirte a este grupo de tutoria?')) { //pide confirmacion al usuario
        // Aqui iria la logica para unirse al grupo
        // Por ahora solo simulamos
        showToast('Te has unido al grupo exitosamente', 'success'); //muestra notificacion de exito
        setTimeout(() => location.reload(), 1500); //recarga la pagina despues de 1.5 segundos
    }
}

function salirGrupo(grupoId) { //funcion para salir de un grupo
    if (confirm('¿Estas seguro que deseas salir de este grupo?')) { //pide confirmacion al usuario
        // Aqui iria la logica para salir del grupo
        showToast('Has salido del grupo', 'info'); //muestra notificacion de informacion
        setTimeout(() => location.href = '/tutorias', 1500); //redirige a la pagina de tutorias despues de 1.5 segundos
    }
}

// Cerrar modal con ESC
document.addEventListener('keydown', (e) => { //maneja el evento keydown
    if (e.key === 'Escape') { //si la tecla presionada es ESC
        cerrarModal(); //cierra el modal
    }
});

// Cerrar modal al hacer click fuera
document.getElementById('modalCodigo')?.addEventListener('click', (e) => { //maneja el click en el fondo del modal
    if (e.target.id === 'modalCodigo') { //si el click fue en el contenedor del modal y no en su contenido
        cerrarModal(); //cierra el modal
    }
});
