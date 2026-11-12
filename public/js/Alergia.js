document.addEventListener('DOMContentLoaded', function () {
    const formAlergia = document.getElementById('formAlergia');
    const btnAgregar = document.getElementById('btnAgregar');
    const inputFiltrar = document.getElementById('filtrar');

    listarAlergias();

    if (inputFiltrar) {
        inputFiltrar.addEventListener('keyup', filtrarTabla);
    }

    if (btnAgregar) {
        btnAgregar.addEventListener('click', function () {
            if (formAlergia) formAlergia.reset();
            const idInput = document.getElementById('id_alergia');
            if (idInput) idInput.value = '';
            const titulo = document.getElementById('TituloModalAlergia');
            if (titulo) titulo.innerText = 'Registrar Alergia';
        });
    }

    if (formAlergia) {
        formAlergia.addEventListener('submit', function (e) {
            e.preventDefault();

            // Capturar y limpiar espacios en blanco
            const nombreInput = document.getElementById('nombre_alergia');
            const tipoInput = document.getElementById('tipo_alergia');
            
            const nombre = nombreInput.value.trim();
            const tipo = tipoInput.value.trim();

            // 1. Validar campos vacíos en frontend
            if (nombre === '' || tipo === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos vacíos',
                    text: 'Por favor completa todos los campos obligatorios.'
                });
                return;
            }

            // 2. Validar longitudes mínimas
            if (nombre.length < 3 || tipo.length < 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Texto muy corto',
                    text: 'Los campos deben tener al menos 3 caracteres.'
                });
                return;
            }

            // 3. Validar caracteres permitidos mediante expresión regular
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s,\.-]+$/;
            if (!regex.test(nombre) || !regex.test(tipo)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Caracteres no válidos',
                    text: 'Evita usar símbolos especiales extraños en los campos.'
                });
                return;
            }

            const formData = new FormData(formAlergia);

            fetch('?url=Alergia&accion=guardar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const modalElement = document.getElementById('ModalAgregar');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    formAlergia.reset();
                    listarAlergias();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudo guardar la alergia'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Servidor',
                    text: 'Ocurrió un error al procesar la solicitud.'
                });
            });
        });
    }
});

function listarAlergias() {
    fetch('?url=Alergia&accion=listar')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('TablaAlergias');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">No hay alergias registradas</td>
                    </tr>`;
                return;
            }

            data.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.id_alergia}</td>
                        <td>${item.nombre_alergia}</td>
                        <td>${item.tipo}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editarAlergia(${item.id_alergia}, '${item.nombre_alergia}', '${item.tipo}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarAlergia(${item.id_alergia})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error('Error al listar:', err));
}

function editarAlergia(id, nombre, tipo) {
    document.getElementById('id_alergia').value = id;
    document.getElementById('nombre_alergia').value = nombre;
    document.getElementById('tipo_alergia').value = tipo;
    document.getElementById('TituloModalAlergia').innerText = 'Editar Alergia';

    const modal = new bootstrap.Modal(document.getElementById('ModalAgregar'));
    modal.show();
}

function eliminarAlergia(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_alergia', id);

            fetch('?url=Alergia&accion=eliminar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('¡Eliminado!', data.message, 'success');
                    listarAlergias();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

function filtrarTabla() {
    let filter = document.getElementById("filtrar").value.toLowerCase();
    let rows = document.querySelectorAll("#TablaAlergias tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}