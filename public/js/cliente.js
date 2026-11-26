
let formularioCliente = document.querySelector(".ClienteForm");
let TablaCliente = document.getElementById("TablaCliente");
let TituloModal = document.getElementById("exampleModalLabel");
let btnAgregar = document.getElementById("btnAgregar");
let filtrar = document.getElementById("filtrar");
let dataTableInstance = null;

// =============================================
// 1. OBTENER DATOS Y PINTAR LA TABLA
// =============================================
function obtenerDatos(param = null) {

    let datos = new FormData();
    datos.append("obtener", true);
    if (param != null) datos.append("parametro", param);

    peticionAjax(datos, function (result) {

        // Destruir DataTable si ya existe
        if (dataTableInstance !== null) {
            dataTableInstance.destroy();
            dataTableInstance = null;
        }

        let tbody = TablaCliente.querySelector("tbody") || TablaCliente;
        let tieneAcciones = document.getElementById('acciones') !== null;

        if (result.status === "success" && result.resultados && result.resultados.length > 0) {

            tbody.innerHTML = "";

            result.resultados.forEach(function (cliente) {
                const acciones = tieneAcciones ? `
                    <td class="table-light input-group">
                        <button class="btn btn-sm btn-success btn-actualizar" value="${cliente.cedula_cliente}" data-bs-toggle="modal" data-bs-target="#exampleModal">Actualizar</button>
                        <button class="btn btn-sm btn-danger btn-eliminar" value="${cliente.cedula_cliente}">Eliminar</button>
                    </td>` : "";

                tbody.innerHTML += `
                    <tr>
                        <td class="table-light">${cliente.cedula_cliente}</td>
                        <td class="table-light">${cliente.nombre}</td>
                        <td class="table-light">${cliente.apellido}</td>
                        <td class="table-light">${cliente.telefono}</td>
                        <td class="table-light">${cliente.correo}</td>
                        <td class="table-light">${cliente.direccion}</td>
                        ${acciones}
                    </tr>`;
            });

        } else {
            let colspan = tieneAcciones ? 7 : 6;
            tbody.innerHTML = `<tr class='table-light text-center'><td colspan="${colspan}">No hay registros de clientes</td></tr>`;
        }

        // ✅ DataTable con paginación de 10 registros y en español
        dataTableInstance = new DataTable('#TablaCliente', {
             scrollX: true,
            searching: true,
            lengthChange: true,
            lengthMenu: [5,10,20],
            pageLength: 5,
            
            layout: {
		    topStart: null,    
		    topEnd: "search",
		    bottomStart: "pageLength", 
		    bottomEnd: "paging"
	        },
            pagingType: "full_numbers",
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty:"Mostrando 0 registros",
                infoFiltered:"(filtrado de _MAX_ registros)",
                paginate: { 
                    
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                 }
            }
        });
    });
}

obtenerDatos();

// =============================================
// 2. BOTÓN REGISTRAR (LIMPIAR MODAL)
// =============================================
if (btnAgregar) {
    btnAgregar.addEventListener("click", function () {
        if (formularioCliente) formularioCliente.reset();
        if (TituloModal) TituloModal.innerText = "Registrar Cliente";
        document.getElementById("is_update").value = "0";
        document.getElementById("cedula_cliente").readOnly = false;

        // Limpiar errores del plugin jQuery Validate
        $('.ClienteForm').validate().resetForm();
        $('.ClienteForm input, .ClienteForm textarea').removeClass("is-invalid is-valid");
        $('.invalid-feedback').empty(); // Limpia los mensajes de error del servidor
    });
}

// =============================================
// 3. CARGAR CLIENTE EN EL MODAL (ACTUALIZAR)
// =============================================
TablaCliente.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-actualizar")) {
        e.preventDefault();
        
        // Limpiar errores antes de cargar los nuevos
        $('.ClienteForm').validate().resetForm();
        $('.ClienteForm input, .ClienteForm textarea').removeClass("is-invalid is-valid");
        $('.invalid-feedback').empty();

        if (TituloModal) TituloModal.innerText = "Actualizar Cliente";
        let id = e.target.value;

        document.getElementById("is_update").value = "1";
        document.getElementById("cedula_cliente").readOnly = true;

        let datos = new FormData();
        datos.append("obtenerCliente", true);
        datos.append("id", id);

        // ✅ AJAX con jQuery
        peticionAjax(datos, function (res) {
            if (res.status === "success" && res.resultado) {
                let result = res.resultado;
                document.getElementById("cedula_cliente").value = result.cedula_cliente;
                document.getElementById("nombre").value = result.nombre;
                document.getElementById("apellido").value = result.apellido;
                document.getElementById("telefono").value = result.telefono;
                document.getElementById("correo").value = result.correo;
                document.getElementById("direccion").value = result.direccion;
            } else {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: 'No se pudo obtener la información del cliente' 
                });
            }
        });
    }
});

// =============================================
// 4. GUARDAR / ACTUALIZAR FORMULARIO
// =============================================
if (formularioCliente) {
    formularioCliente.addEventListener("submit", function (e) {
        e.preventDefault();

        // 🛑 FRENO DE MANO: Si la validación de jQuery falla, frena todo.
        if (!$('.ClienteForm').valid()) {
            return;
        }

        // Limpiamos errores visuales previos del servidor
        $('.ClienteForm input, .ClienteForm textarea').removeClass("is-invalid");
        $('.invalid-feedback').empty();

        let datos = new FormData(formularioCliente);
        let is_update = document.getElementById("is_update").value;

        if (is_update === "0") {
            datos.append("agregar", true);
        } else {
            datos.append("actualizar", true);
            datos.append("id", document.getElementById("cedula_cliente").value);
        }

        // ✅ AJAX con jQuery
        peticionAjax(datos, function (res) {
            if (res.status === "success") {
                let msj = is_update === "0" ? "Cliente registrado exitosamente" : "Cliente actualizado exitosamente";
                
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
                // Pintar los errores del servidor (backend)
                if (res.errors) {
                    if (res.errors.cedula) {
                        $("#cedula_cliente").addClass("is-invalid");
                        $("#error-cedula").text(res.errors.cedula);
                    }
                    if (res.errors.nombre) {
                        $("#nombre").addClass("is-invalid");
                        $("#error-nombre").text(res.errors.nombre);
                    }
                    if (res.errors.apellido) {
                        $("#apellido").addClass("is-invalid");
                        $("#error-apellido").text(res.errors.apellido);
                    }
                    if (res.errors.telefono) {
                        $("#telefono").addClass("is-invalid");
                        $("#error-telefono").text(res.errors.telefono);
                    }
                    if (res.errors.correo) {
                        $("#correo").addClass("is-invalid");
                        $("#error-correo").text(res.errors.correo);
                    }
                    if (res.errors.direccion) {
                        $("#direccion").addClass("is-invalid");
                        $("#error-direccion").text(res.errors.direccion);
                    }
                }

                if (res.message) {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            }
        });
    });
}

// =============================================
// 5. ELIMINAR CLIENTE
// =============================================
TablaCliente.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault();
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Este cliente será eliminado del sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                peticionAjax(datos, function (res) {
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'Cliente eliminado con éxito.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        obtenerDatos();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'No se pudo eliminar el cliente'
                        });
                    }
                });
            }
        });
    }
});

// =============================================
// 6. FILTRO POR TECLADO (Buscador externo)
// =============================================
if (filtrar) {
    filtrar.addEventListener("input", function () {
        obtenerDatos(this.value);
    });
}