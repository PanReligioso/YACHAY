// tutorias-show.js

function mostrarModalCodigo() {
    const modal = document.getElementById('modalCodigo');
    modal.style.display = 'flex';
}

function cerrarModal() {
    const modal = document.getElementById('modalCodigo');
    modal.style.display = 'none';
    document.getElementById('codigoInput').value = '';
}

function verificarCodigo(grupoId, codigoCorrecto) {
    const codigoIngresado = document.getElementById('codigoInput').value.trim();

    if (codigoIngresado === '') {
        alert('Por favor ingresa el código de acceso');
        return;
    }

    if (codigoIngresado === codigoCorrecto) {
        unirseGrupo(grupoId);
        cerrarModal();
    } else {
        alert('Código incorrecto. Verifica con el creador del grupo.');
        document.getElementById('codigoInput').value = '';
    }
}

function unirseGrupo(grupoId) {
    if (confirm('¿Deseas unirte a este grupo de tutoría?')) {
        // Aquí iría la lógica para unirse al grupo
        // Por ahora solo simulamos
        showToast('Te has unido al grupo exitosamente', 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

function salirGrupo(grupoId) {
    if (confirm('¿Estás seguro que deseas salir de este grupo?')) {
        // Aquí iría la lógica para salir del grupo
        showToast('Has salido del grupo', 'info');
        setTimeout(() => location.href = '/tutorias', 1500);
    }
}

// Cerrar modal con ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        cerrarModal();
    }
});

// Cerrar modal al hacer click fuera
document.getElementById('modalCodigo')?.addEventListener('click', (e) => {
    if (e.target.id === 'modalCodigo') {
        cerrarModal();
    }
});
