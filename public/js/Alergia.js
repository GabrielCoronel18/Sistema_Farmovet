document.addEventListener('DOMContentLoaded', function () {
    const formAlergia = document.getElementById('formAlergia');
    const btnAgregar = document.getElementById('btnAgregar');
    const inputFiltrar = document.getElementById('filtrar');

    // Cargar la tabla al iniciar la vista
    listarAlergias();

    // Filtro de búsqueda
    if (inputFiltrar) {
        inputFiltrar.addEventListener('keyup', filtrarTabla);
    }

    // Resetear el modal para nuevos registros
    if (btnAgregar) {
        btnAgregar.addEventListener('click', function () {
            if (formAlergia) formAlergia.reset();
            const idInput = document.getElementById('id_alergia');
            if (idInput) idInput.value = '';
            const titulo = document.getElementById('TituloModalAlergia');
            if (titulo) titulo.innerText = 'Agregar Alergia';
        });
    }

    // Procesar el envío del formulario mediante Fetch / AJAX
    if (formAlergia) {
        formAlergia.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(formAlergia);
            const id = document.getElementById('id_alergia').value;
            formData.append(id === '' ? 'agregar' : 'actualizar', true);


            peticionAjax(formData)
            .then(data => {
                if (data.status === 'success') {
                    // Ocultar Modal de Bootstrap
                    const modalElement = document.getElementById('ModalAgregar');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();

                    let message = data.message || null;

                   
                    id === "" ? alertAgregar("success",message) : alertActualizar("success",message);

                    formAlergia.reset();
                    listarAlergias();
                } else {
                 
                    id === "" ? alertAgregar("error",message) : alertActualizar("error", message);
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

// Obtención de datos AJAX para llenar la tabla
function listarAlergias() {
    
      let datos = new FormData();
      datos.append('consultar', true);

      peticionAjax(datos).then(alergias => {

            const tbody = document.getElementById('TablaAlergias');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (!alergias || alergias.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-3 text-muted">No hay alergias registradas</td>
                    </tr>`;
                return;
            }

            alergias.resultados.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.id_alergia}</td>
                        <td>${item.nombre_alergia}</td>
                        <td>${item.tipo}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success btn-actualizar" onclick="actualizarAlergia(${item.id_alergia})">
                                Actualizar
                            </button>
                            <button class="btn btn-sm btn-danger btn-eliminar" onclick="eliminarAlergia(${item.id_alergia})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error('Error al listar:', err));
}

function actualizarAlergia(id) {

    let formData = new FormData();
    formData.append('consultarPorId', true);
    formData.append('id', id);
    peticionAjax(formData).then(data => {
        document.getElementById('id_alergia').value = id;
        document.getElementById('nombre').value = data.resultado.nombre_alergia;
        document.getElementById('tipo').value = data.resultado.tipo;
        document.getElementById('TituloModalAlergia').innerText = 'Editar Alergia';
    });

    const modal = new bootstrap.Modal(document.getElementById('ModalAgregar'));
    modal.show();
}

function eliminarAlergia(id) {
    datos = new FormData();
    datos.append('eliminar', true);
    datos.append('id', id);

    alertEliminar('POST', datos, function() {
        listarAlergias();
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