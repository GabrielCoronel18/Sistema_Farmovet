// =============================================
// razas.js - Lógica del módulo Razas
// DOM: vanilla JS | AJAX: jQuery (peticionAjax)
// =============================================

let formularioRaza = document.querySelector(".RazaForm");
let TablaRazas = document.getElementById("TablaRazas");
let TituloModal = document.getElementById("exampleModalLabel");
let btnAgregar = document.getElementById("btnAgregar");
let dataTableInstance = null;

// =============================================
// 1. OBTENER DATOS Y PINTAR LA TABLA
// =============================================
function obtenerDatos(param = null) {

    let datos = new FormData();
    datos.append("obtener", true);
    if (param != null) datos.append("parametro", param);

    peticionAjax(datos, function (result) {

        if (dataTableInstance !== null) {
            dataTableInstance.destroy();
            dataTableInstance = null;
        }

        let tbody = TablaRazas.querySelector("tbody") || TablaRazas;

        if (result.status === "success" && result.resultados && result.resultados.length > 0) {

            tbody.innerHTML = "";

            result.resultados.forEach(function (raza) {
                let id = raza.id_raza;
                let nombre = raza.nombre_raza;
                let especie = raza.nombre_especie || raza.especie || "N/A";

                const acciones = `<td class="table-light">
                            <button class="btn btn-sm btn-success" onclick="cargarRaza('${id}')" data-bs-toggle="modal" data-bs-target="#exampleModal">Actualizar</button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarRaza('${id}')">Eliminar</button>
                        </td>`;

                tbody.innerHTML += `
                    <tr>
                        <td class="table-light">${id}</td>
                        <td class="table-light">${nombre}</td>
                        <td class="table-light">${especie}</td>
                        ${document.getElementById('acciones') ? acciones : ''}
                    </tr>`;
            });

        } else {
            let colspan = document.getElementById('acciones') ? 4 : 3;
            tbody.innerHTML = `<tr class='table-light text-center'><td colspan="${colspan}">No hay registros de razas</td></tr>`;
        }

        dataTableInstance = new DataTable('#TablaRazas', {
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
// 2. CARGAR RAZA EN EL MODAL (ACTUALIZAR)
// =============================================
window.cargarRaza = function (id) {

    $("#RazaForm").validate().resetForm();
    $("#nombre_raza, #id_especie").removeClass("is-invalid is-valid");

    if (TituloModal) TituloModal.innerText = "Actualizar Raza";
    document.getElementById("is_update").value = "1";

    let datos = new FormData();
    datos.append("obtenerRaza", true);
    datos.append("id_raza", id);

    peticionAjax(datos, function (res) {

        if (res.status === "success" && res.resultado) {
            document.getElementById("id_raza").value = res.resultado.id_raza;
            document.getElementById("nombre_raza").value = res.resultado.nombre_raza;
            document.getElementById("id_especie").value = res.resultado.id_especie;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo obtener la información de la raza'
            });
        }
    });
};

// =============================================
// 3. ELIMINAR RAZA (con confirmación)
// =============================================
window.eliminarRaza = function (id) {

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta raza será eliminada del sistema.",
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
            datos.append("id_raza", id);

            peticionAjax(datos, function (res) {

                if (res.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'Raza eliminada con éxito.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    obtenerDatos();
                } else {
                    const error = Object.values(res.errors || {})[0];
                    if (error) {
                        $("#nombre_raza").addClass("is-invalid");
                        // 🔴 CORREGIDO: guion en vez de guion bajo
                        $("#error-nombre-raza").text(error).addClass("d-block");
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message || 'No se pudo eliminar la raza'
                    });
                }
            });
        }
    });
};

// =============================================
// 4. BOTÓN REGISTRAR (LIMPIAR MODAL)
// =============================================
if (btnAgregar) {
    btnAgregar.addEventListener("click", function () {
        if (formularioRaza) formularioRaza.reset();
        if (TituloModal) TituloModal.innerText = "Registrar Raza";
        document.getElementById("is_update").value = "0";
        document.getElementById("id_raza").value = "";
        
        // 🔴 CORREGIDO: Limpiar el select también
        document.getElementById("id_especie").value = ""; 

        $("#RazaForm").validate().resetForm();
        $("#nombre_raza, #id_especie").removeClass("is-invalid is-valid");
    });
}

// =============================================
// 5. GUARDAR / ACTUALIZAR FORMULARIO (AJAX y validación)
// =============================================
if (formularioRaza) {
    formularioRaza.addEventListener("submit", function (e) {
        e.preventDefault();
        
        if (!$("#RazaForm").valid()) {  
            return; 
        } 

        let datos = new FormData(formularioRaza);
        let is_update = document.getElementById("is_update").value;

        if (is_update === "0") {
            datos.append("agregar", true);
        } else {
            datos.append("actualizar", true);
        }

        peticionAjax(datos, function (res) {
            if (res.status === "success") {
                let msj = is_update === "0" ? "Raza registrada exitosamente" : "Raza actualizada exitosamente";
                
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
                const error = Object.values(res.errors || {})[0];
                if (error) {
                    $("#nombre_raza").addClass("is-invalid");
                    // 🔴 CORREGIDO: guion en vez de guion bajo
                    $("#error-nombre-raza").text(error).addClass("d-block");
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message || 'Error al realizar la operación'
                });
            }
        });
    });
}