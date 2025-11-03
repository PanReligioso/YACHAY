// tutorias-crear.js

function actualizarTipo(tipo) {
    const codigoDiv = document.getElementById('codigoDiv');
    const badge = document.getElementById('preview-badge');

    if (tipo === 'privado') {
        codigoDiv.style.display = 'block';
        document.getElementById('codigo').required = true;
        badge.innerHTML = '<i class="fas fa-lock"></i> Privado';
        badge.style.background = 'var(--accent-orange)';
    } else {
        codigoDiv.style.display = 'none';
        document.getElementById('codigo').required = false;
        badge.innerHTML = '<i class="fas fa-globe"></i> Público';
        badge.style.background = 'var(--accent-green)';
    }
}

function actualizarPreview() {
    const nombre = document.getElementById('nombre').value || 'Nombre del grupo...';
    const descripcion = document.getElementById('descripcion').value || 'Descripción del grupo...';
    const max = document.getElementById('max').value || '50';
    const cursoSelect = document.getElementById('curso');
    const cursoTexto = cursoSelect.options[cursoSelect.selectedIndex].text;

    // Actualizar preview
    document.getElementById('preview-nombre').textContent = nombre;
    document.getElementById('preview-nombre').style.color = nombre === 'Nombre del grupo...' ? 'var(--text-tertiary)' : 'var(--text-primary)';

    document.getElementById('preview-descripcion').textContent = descripcion;
    document.getElementById('preview-descripcion').style.color = descripcion === 'Descripción del grupo...' ? 'var(--text-tertiary)' : 'var(--text-secondary)';

    document.getElementById('preview-max').textContent = `0 / ${max} participantes`;

    const cursoDiv = document.getElementById('preview-curso-div');
    if (cursoSelect.value) {
        cursoDiv.style.display = 'flex';
        document.getElementById('preview-curso').textContent = cursoTexto;
    } else {
        cursoDiv.style.display = 'none';
    }
}

// Contador de caracteres para descripción
const descripcionTextarea = document.getElementById('descripcion');
if (descripcionTextarea) {
    const maxLength = 1000;
    const counterDiv = document.createElement('div');
    counterDiv.style.cssText = 'text-align: right; font-size: var(--text-sm); color: var(--text-tertiary); margin-top: var(--spacing-xs);';
    counterDiv.innerHTML = `<span id="charCount">0</span> / ${maxLength} caracteres`;
    descripcionTextarea.parentNode.appendChild(counterDiv);

    descripcionTextarea.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count;

        if (count > maxLength) {
            counterDiv.style.color = 'var(--accent-red)';
            this.value = this.value.substring(0, maxLength);
        } else if (count > maxLength * 0.9) {
            counterDiv.style.color = 'var(--accent-orange)';
        } else {
            counterDiv.style.color = 'var(--text-tertiary)';
        }
    });
}

// Generar código aleatorio
function generarCodigo() {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let codigo = '';
    for (let i = 0; i < 8; i++) {
        codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }
    document.getElementById('codigo').value = codigo;
    actualizarPreview();
}

// Agregar botón de generar código
const codigoInput = document.getElementById('codigo');
if (codigoInput) {
    const generarBtn = document.createElement('button');
    generarBtn.type = 'button';
    generarBtn.className = 'btn btn-secondary';
    generarBtn.innerHTML = '<i class="fas fa-random"></i> Generar';
    generarBtn.style.cssText = 'margin-top: var(--spacing-sm);';
    generarBtn.onclick = generarCodigo;
    codigoInput.parentNode.appendChild(generarBtn);
}

// Validación en tiempo real
document.getElementById('nombre')?.addEventListener('blur', function() {
    if (this.value.trim().length < 5) {
        this.style.borderColor = 'var(--accent-red)';
        showToast('El nombre debe tener al menos 5 caracteres', 'error');
    } else {
        this.style.borderColor = 'var(--primary-200)';
    }
});

document.getElementById('descripcion')?.addEventListener('blur', function() {
    if (this.value.trim().length < 20) {
        this.style.borderColor = 'var(--accent-red)';
        showToast('La descripción debe tener al menos 20 caracteres', 'error');
    } else {
        this.style.borderColor = 'var(--primary-200)';
    }
});

// Confirmar antes de salir si hay cambios
let formModificado = false;
document.querySelectorAll('#nombre, #descripcion, #curso, input[name="tipo"], #max, #codigo').forEach(input => {
    input.addEventListener('input', () => formModificado = true);
});

window.addEventListener('beforeunload', (e) => {
    if (formModificado) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// No mostrar alerta al enviar formulario
document.querySelector('form')?.addEventListener('submit', () => {
    formModificado = false;
});
