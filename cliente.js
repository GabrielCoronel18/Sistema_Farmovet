const modalElemento = document.getElementById('exampleModal');
let miModal;

if (modalElemento) {
    miModal = new bootstrap.Modal(modalElemento);
}

const botonesModificar = document.querySelectorAll('.btn-modificar');
botonesModificar.forEach(boton => {
    boton.addEventListener('click', (e) => {
        e.preventDefault();

        // Obtener datos del boton
        const cedula = boton.getAttribute('data-cedula');
        const nombre = boton.getAttribute('data-nombre');
        const apellido = boton.getAttribute('data-apellido');
        const correo = boton.getAttribute('data-correo');
        const telefono = boton.getAttribute('data-telefono');
        const direccion = boton.getAttribute('data-direccion');

        // Llenar el formulario
        document.querySelector('input[name="cedula"]').value = cedula;
        document.querySelector('input[name="nombre"]').value = nombre;
        document.querySelector('input[name="apellido"]').value = apellido;
        document.querySelector('input[name="correo"]').value = correo;
        document.querySelector('input[name="telefono"]').value = telefono;
        document.querySelector('input[name="direccion"]').value = direccion;

        // Cambiar titulo y nombre del boton submit para Modificar
        document.getElementById('exampleModalLabel').innerText = 'Modificar Cliente';
        const submitBtn = document.querySelector('button[name="enviar"]') || document.querySelector('button[name="modificar"]');
        submitBtn.name = 'modificar';
        submitBtn.innerText = 'Actualizar';
        
        // Bloquear cedula
        document.querySelector('input[name="cedula"]').setAttribute('readonly', true);

        miModal.show();
    });
});

// Resetear modal al presionar "Registrar Cliente"
const btnRegistrar = document.querySelector('[data-bs-target="#exampleModal"]');
if (btnRegistrar) {
    btnRegistrar.addEventListener('click', () => {
        document.querySelector('form').reset();
        document.getElementById('exampleModalLabel').innerText = 'Registrar Cliente';
        const submitBtn = document.querySelector('button[name="modificar"]') || document.querySelector('button[name="enviar"]');
        if (submitBtn) {
            submitBtn.name = 'enviar';
            submitBtn.innerText = 'Guardar';
        }
        document.querySelector('input[name="cedula"]').removeAttribute('readonly');
    });
}

// SweetAlert para Confirmar Eliminación
const botonesEliminar = document.querySelectorAll('.btn-eliminar');
botonesEliminar.forEach(boton => {
    boton.addEventListener('click', (e) => {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El cliente será eliminado de la lista.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar el formulario padre
                boton.closest('form').submit();
            }
        });
    });
});