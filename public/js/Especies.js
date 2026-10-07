let formularioEspecie = document.querySelector(".EspeciesForm");
let TablaEspecies = document.getElementById("TablaEspecies");
let TituloModal = document.getElementById("exampleModalLabel");
let btnAgregar = document.getElementById("btnAgregar");
let dataTableInstance = null;

function obtenerDatos(param = null) {
    let datos = new FormData();
    datos.append("obtener", true);
    if (param != null) datos.append("parametro", param);

    fetch(window.location, { method: "post", body: datos })
        .then(res => res.json())
        .then(result => {
            if (dataTableInstance !== null) {
                dataTableInstance.destroy();
            }

            let tbody = TablaEspecies.querySelector("tbody") || TablaEspecies;

            if (result.status === "success" && result.resultados && result.resultados.length > 0) {
                tbody.innerHTML = "";
                result.resultados.forEach(function (especie) {
                    let id = especie.id_especie;
                    let nombre = especie.nombre_especie;

                    tbody.innerHTML += `
                        <tr>
                            <td class="table-light">${id}</td>
                            <td class="table-light">${nombre}</td>
                            <td class="table-light">
                                <button class="btn btn-sm btn-success" onclick="cargarEspecie('${id}')" data-bs-toggle="modal" data-bs-target="#exampleModal">Actualizar</button> 
                                <button class="btn btn-sm btn-danger" onclick="eliminarEspecie('${id}')">Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = `<tr class='table-light text-center'><td colspan="3">No hay registros de especies</td></tr>`;
            }

            dataTableInstance = new DataTable('#TablaEspecies', {
                scrollX: true,
                searching: true,
                lengthChange: false,
                language: {
                    search: "Buscar:",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: { previous: "Anterior", next: "Siguiente" }
                }
            });
        })
        .catch(err => console.error("Error al obtener lista:", err));
}

obtenerDatos();

// CARGAR DATOS EN EL MODAL AL PRESIONAR ACTUALIZAR
window.cargarEspecie = function(id) {
    if (TituloModal) TituloModal.innerText = "Actualizar Especie";
    document.getElementById("is_update").value = "1";

    let datos = new FormData();
    datos.append("obtenerEspecie", true);
    datos.append("id_especie", id);

    fetch(window.location, { method: "post", body: datos })
        .then(res => res.json())
        .then(res => {
            if (res.status === "success" && res.resultado) {
                let data = res.resultado;
                document.getElementById("id_especie").value = data.id_especie;
                document.getElementById("nombre_especie").value = data.nombre_especie;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo obtener la información de la especie'
                });
            }
        })
        .catch(err => console.error("Error al consultar especie:", err));
};

// ELIMINAR ESPECIE CON VENTANA FLOTANTE DE CONFIRMACIÓN
window.eliminarEspecie = function(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta especie será eliminada del sistema.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            let datos = new FormData();
            datos.append("eliminar", true);
            datos.append("id_especie", id);

            fetch(window.location, { method: "post", body: datos })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'Especie eliminada con éxito.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        obtenerDatos();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'No se pudo eliminar la especie'
                        });
                    }
                })
                .catch(err => console.error("Error al eliminar:", err));
        }
    });
};

// BOTÓN REGISTRAR (LIMPIAR MODAL)
if (btnAgregar) {
    btnAgregar.addEventListener("click", function () {
        if (formularioEspecie) formularioEspecie.reset();
        if (TituloModal) TituloModal.innerText = "Registrar Especie";
        document.getElementById("is_update").value = "0";
        document.getElementById("id_especie").value = "";
    });
}

// GUARDAR / ACTUALIZAR FORMULARIO CON SweetAlert2
if (formularioEspecie) {
    formularioEspecie.addEventListener("submit", function (e) {
        e.preventDefault();
        let datos = new FormData(formularioEspecie);
        let is_update = document.getElementById("is_update").value;

        if (is_update === "0") {
            datos.append("agregar", true);
        } else {
            datos.append("actualizar", true);
        }

        fetch(window.location, { method: "post", body: datos })
            .then(res => res.json())
            .then(res => {
                if (res.status === "success") {
                    let msj = is_update === "0" ? "Especie registrada exitosamente" : "Especie actualizada exitosamente";
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: msj,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    let modalElement = document.getElementById("exampleModal");
                    let modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();

                    obtenerDatos();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message || 'Error al realizar la operación'
                    });
                }
            })
            .catch(err => console.error("Error al enviar formulario:", err));
    });
}