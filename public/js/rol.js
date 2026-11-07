const urlParams = new URLSearchParams(window.location.search);
    const accion = urlParams.get('accion') || 'crear';
    const id = urlParams.get('id');
    const campoNombre = document.getElementById('nombre');
    const errorNombre = document.getElementById('error-nombre');

    if (accion === 'editar' && id) {
        document.getElementById('titulo-form').textContent = 'Editar Rol';
        document.getElementById('btn-submit').innerHTML = '<i class="bi bi-save"></i> Actualizar';
        const fd = new FormData();
        fd.append('obtenerRol', '1');
        fd.append('id', id);
        fetch('?url=Rol', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('id').value = data.resultado.id_rol;
                    document.getElementById('nombre').value = data.resultado.nombre_rol;
                } else {
                    Swal.fire('Error', 'No se encontró el rol', 'error').then(() => window.location.href = '?url=Rol');
                }
            });
    }

    document.getElementById('form-rol').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        if (accion === 'editar') {
            formData.append('actualizar', '1');
        } else {
            formData.append('agregar', '1');
        }

        fetch('?url=Rol', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: accion === 'crear' ? '¡Rol creado!' : '¡Rol actualizado!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => window.location.href = '?url=Rol');
                } else {
                    if (data.errors && data.errors.nombre) {
                        errorNombre.textContent = data.errors.nombre;
                        campoNombre.classList.add('is-invalid');
                    }
                    Swal.fire('Error', data.mensaje || 'Operación fallida', 'error');
                }
            });
    });