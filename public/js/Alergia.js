document.addEventListener('DOMContentLoaded', function () {
    const formAlergia = document.getElementById('formAlergia');
    const btnAgregar = document.getElementById('btnAgregar');
    const errorNombre = document.getElementById('errorNombre');
    const errorTipo = document.getElementById('errorTipo');
    const campoNombre = document.getElementById('nombre');
    const campoTipo = document.getElementById('tipo');
    const inputFiltrar = document.getElementById('filtrar');

    function limpiarErroresFormulario() {
        $('#formAlergia').validate().resetForm();
        [campoNombre, campoTipo].forEach(campo => campo.classList.remove('is-invalid'));
        [errorNombre, errorTipo].forEach(error => error.textContent = '');
    }

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
            limpiarErroresFormulario();
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
            if (!$(formAlergia).valid()) return;
            const formData = new FormData(formAlergia);
            const id = document.getElementById('id_alergia').value;
            formData.append(id === '' ? 'agregar' : 'actualizar', true);

            peticionAjax(formData)
            .then(data => {
                if (data.status === 'success') {
                    const modalElement = document.getElementById('ModalAgregar');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();

                    id === "" ? alertAgregar("success",data.message) : alertActualizar("success",data.message);

                    formAlergia.reset();
                    limpiarErroresFormulario();
                    listarAlergias();
                } 
                else if (data.status === 'error') {
                    const errors = data.errors || {};
                    if (errors.nombre) {
                        campoNombre.classList.add("is-invalid");
                        errorNombre.textContent = errors.nombre;
                    }
                    if (errors.tipo) {
                        campoTipo.classList.add("is-invalid");
                        errorTipo.textContent = errors.tipo;
                    }
                    id === "" ? alertAgregar("error", data.message) : alertActualizar("error", data.message);
                }
            })
            .catch(error => {
                console.error('Error al guardar la alergia:', error);
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
                const acciones = `<td class="text-center">
                            <button class="btn btn-sm btn-success btn-actualizar" onclick="actualizarAlergia(${item.id_alergia})">
                                Actualizar
                            </button>
                            <button class="btn btn-sm btn-danger btn-eliminar" onclick="eliminarAlergia(${item.id_alergia})">
                                Eliminar
                            </button>
                        </td>`;

                        
                tbody.innerHTML += `
                    <tr>
                        <td>${item.id_alergia}</td>
                        <td>${item.nombre_alergia}</td>
                        <td>${item.tipo}</td>
                        
                        ${document.getElementById('acciones') ? acciones : ''}
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

    const formAlergia = document.getElementById('formAlergia');
    $('#formAlergia').validate().resetForm();
    formAlergia.querySelectorAll('.is-invalid').forEach(campo => campo.classList.remove('is-invalid'));
    document.getElementById('errorNombre').textContent = '';
    document.getElementById('errorTipo').textContent = '';

    return peticionAjax(formData).then(data => {
        if (data.status !== 'success' || !data.resultado) {
            Swal.fire('Error', 'No se pudo cargar la alergia seleccionada.', 'error');
            return;
        }

        document.getElementById('id_alergia').value = id;
        document.getElementById('nombre').value = data.resultado.nombre_alergia;
        document.getElementById('tipo').value = data.resultado.tipo;
        document.getElementById('TituloModalAlergia').innerText = 'Editar Alergia';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('ModalAgregar')).show();
    }).catch(error => {
        console.error('Error al cargar la alergia:', error);
    });
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