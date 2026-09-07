let formularioCliente = document.querySelector(".ClienteForm");
let TablaCliente = document.getElementById("TablaCliente");
let TituloModal = document.getElementById("exampleModalLabel");
let btnAgregar = document.getElementById("btnAgregar");
let filtrar = document.getElementById("filtrar");

// Variable global para almacenar la instancia de la tabla
let dataTableInstance = null;

function obtenerDatos(param = null) {
    let datos = new FormData();
    datos.append("obtener", true);

    if (param != null) {
        datos.append("parametro", param);
    }

    fetch(window.location, { method: "post", body: datos })
        .then(resultados => resultados.json())
        .then(result => {

            // 1. SI YA EXISTE UNA INSTANCIA DE DATATABLE, LA DESTRUIMOS
            if (dataTableInstance !== null) {
                dataTableInstance.destroy();
            }

            // Seleccionamos el cuerpo de la tabla (tbody) para insertar las filas
            let tbody = TablaCliente.querySelector("tbody");
            if (!tbody) tbody = TablaCliente;

            if (result.status === "success") {
                tbody.innerHTML = "";
                result.resultados.forEach(function (cliente) {
                    tbody.innerHTML +=
                        `<tr>
                    <td class="table-light">${cliente.cedula_cliente}</td>
                    <td class="table-light">${cliente.nombre}</td>
                    <td class="table-light">${cliente.apellido}</td>
                    <td class="table-light">${cliente.telefono}</td>
                    <td class="table-light">${cliente.correo}</td>
                    <td class="table-light">${cliente.direccion}</td>
                    <td class="table-light input-group">
                        <button class="btn btn-sm btn-success btn-actualizar" value="${cliente.cedula_cliente}" data-bs-toggle="modal" data-bs-target="#exampleModal">Actualizar</button> 
                        <button class="btn btn-sm btn-danger btn-eliminar" value="${cliente.cedula_cliente}">Eliminar</button>
                    </td>
                </tr>`;
                });
            } else if (result.status === "error") {
                tbody.innerHTML = `
                <tr class='table-light text-center'>
                <td colspan="7">No se han encontrado registros de clientes</td>
                </tr>`;
            }

            dataTableInstance = new DataTable('#TablaCliente', {
                "columnDefs": [
                    { "width": "20%", "targets": 6 }, // Primera columna
                ],

                scrollX: true,
                searching: true,
                lengthChange: false,
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "Ningún dato disponible en esta tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });
}


obtenerDatos();

btnAgregar.addEventListener("click", function (e) {
    formularioCliente.reset();
    TituloModal.innerText = "Agregar Nuevo Cliente";
    document.getElementById("cedula_cliente").readOnly = false;
    document.getElementById("is_update").value = "0";
});

TablaCliente.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-actualizar")) {
        e.preventDefault();
        TituloModal.innerText = "Actualizar Cliente";
        let id = e.target.value;

        document.getElementById("is_update").value = "1";
        document.getElementById("cedula_cliente").readOnly = true;

        let datos = new FormData();
        datos.append("obtenerCliente", true);
        datos.append("id", id);

        fetch(window.location, { method: "post", body: datos })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                let result = resultado.resultado;
                if (resultado.status === "success") {
                    document.getElementById("cedula_cliente").value = result.cedula_cliente;
                    document.getElementById("nombre").value = result.nombre;
                    document.getElementById("apellido").value = result.apellido;
                    document.getElementById("telefono").value = result.telefono;
                    document.getElementById("correo").value = result.correo;
                    document.getElementById("direccion").value = result.direccion;
                } else if (resultado.status === "error") {
                    Swal.fire({ title: "Error", text: "Error al obtener el registro", icon: "error" });
                }
            });
    }
});

formularioCliente.addEventListener("submit", function (e) {
    e.preventDefault();
    let datos = new FormData(formularioCliente);
    let is_update = document.getElementById("is_update").value;

    if (is_update === "0") {
        datos.append("agregar", true);
    } else {
        datos.append("actualizar", true);
        datos.append("id", document.getElementById("cedula_cliente").value);
    }

    fetch(window.location, { method: "post", body: datos })
        .then(respuesta => respuesta.json())
        .then(resultado => {
            if (resultado.status === "success") {
                is_update === "0" ? alertRegistrarCliente("success") : alertActualizarCliente("success");
                let modalElement = document.getElementById("exampleModal");
                let ModalAgregar = bootstrap.Modal.getInstance(modalElement);
                ModalAgregar.hide();
                obtenerDatos(); // Recarga la tabla y reconstruye DataTables
            } else if (resultado.status === "error") {
                if (resultado.message) {
                    Swal.fire({ title: "Error", text: resultado.message, icon: "error" });
                } else {
                    is_update === "0" ? alertRegistrarCliente("error") : alertActualizarCliente("error");
                }
            }
        });
});

TablaCliente.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault();
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);
        alertEliminarCliente("post", datos, obtenerDatos);
    }
});

// Filtro personalizado por teclado
filtrar.addEventListener("input", function () {
    let param = this.value;
    obtenerDatos(param);
});
