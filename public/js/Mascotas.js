let formularioMascotas = document.querySelector(".MascotasForm");
let TablaMascotas = document.getElementById("TablaMascotas");
let TituloModal = document.getElementById("TituloModalMascotas");
let btnAgregar = document.getElementById("btnAgregar");
let filtrar = document.getElementById("filtrar");
let selectRaza = document.getElementById("id_raza");
let selectCliente = document.getElementById("cedula_cliente");
const camposValidacion = [
    { error: "nombre", campo: "nombre", mensaje: "error-nombre" },
    { error: "edad", campo: "edad", mensaje: "error-edad" },
    { error: "fch_nacimiento", campo: "fch_nacimiento", mensaje: "error-fch_nacimiento" },
    { error: "sexo", campo: "sexo", mensaje: "error-sexo" },
    { error: "chip", campo: "chip", mensaje: "error-chip" },
    { error: "raza", campo: "id_raza", mensaje: "error-id_raza" },
    { error: "pelaje", campo: "pelaje", mensaje: "error-pelaje" },
    { error: "cedula", campo: "cedula_cliente", mensaje: "error-cedula_cliente" },
    { error: "procedencia", campo: "procedencia", mensaje: "error-procedencia" }
];


let selectAlergias;
let selectEnfermedades;
let selectCirugias;

function limpiarErroresValidacion() {
    camposValidacion.forEach(({ campo, mensaje }) => {
        document.getElementById(campo).classList.remove("is-invalid");
        document.getElementById(mensaje).textContent = "";
    });
}

function renderizarErroresValidacion(errores = {}) {
    camposValidacion.forEach(({ error, campo, mensaje }) => {
        const mensajeError = errores[error];
        if (mensajeError) {
            document.getElementById(campo).classList.add("is-invalid");
            document.getElementById(mensaje).textContent = mensajeError;
        }
    });
}

function inicializarSelectsAntecedentes() {
    selectAlergias = new TomSelect("#mascota_alergias", {
    plugins: ["remove_button"],
    create: false,
    persist: false,
    placeholder: "Seleccione una o varias alergias"
    });
    selectEnfermedades = new TomSelect("#mascota_enfermedades", {
    plugins: ["remove_button"],
    create: false,
    persist: false,
    placeholder: "Seleccione una o varias enfermedades"
    });
    selectCirugias = new TomSelect("#mascota_cirugias", {
    plugins: ["remove_button"],
    create: false,
    persist: false,
    placeholder: "Seleccione una o varias cirugías"
    });
}

function cargarCatalogos() {
    const datos = new FormData();
    datos.append("obtenerCatalogos", true);

    return fetch(window.location, { method: "post", body: datos })
        .then(respuesta => respuesta.json())
        .then(resultado => {
            if (resultado.status !== "success") {
                throw new Error("No se pudieron obtener los catálogos.");
            }

            resultado.razas.forEach(raza => {
                selectRaza.add(new Option(raza.nombre_raza, raza.id_raza));
            });

            resultado.clientes.forEach(cliente => {
                const nombre = `${cliente.nombre} ${cliente.apellido} (${cliente.cedula_cliente})`;
                selectCliente.add(new Option(nombre, cliente.cedula_cliente));
            });

            inicializarSelectsAntecedentes();
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                title: "Error",
                text: "No se pudieron cargar los catálogos de razas y clientes.",
                icon: "error"
            });
            throw error;
        });
}

function obtenerDatos(param = null){
    let datos = new FormData();
    datos.append("obtener", true);

    if(param != null){
        datos.append("parametro", param);
    }

    fetch(window.location, {method: "post", body: datos})
    .then(resultados => resultados.json())
    .then(result => {
        if (result.status === "success") {
            TablaMascotas.innerHTML = "";
            
            result.resultados.forEach(function(mascota){
                let alergiasBadge = mascota.alergias 
                    ? `<span class="badge bg-success">${mascota.alergias}</span>` 
                    : `<span class="badge bg-secondary">Ninguna</span>`;

                let enfermedadesBadge = mascota.enfermedades 
                    ? `<span class="badge bg-success">${mascota.enfermedades}</span>` 
                    : `<span class="badge bg-secondary">Ninguna</span>`;

                let cirugiasBadge = mascota.cirugias 
                    ? `<span class="badge bg-success">${mascota.cirugias}</span>` 
                    : `<span class="badge bg-secondary">Ninguna</span>`;

                TablaMascotas.innerHTML += 
                `<tr>
                    <td class="table-light">${mascota.id_mascota}</td>
                    <td class="table-light">${mascota.nombre}</td>
                    <td class="table-light">${mascota.edad}</td>
                    <td class="table-light">${mascota.sexo}</td>
                    <td class="table-light">${mascota.chip}</td>
                    <td class="table-light td-large">${mascota.procedencia}</td>
                    <td class="table-light">${mascota.fch_nacimiento}</td>
                    <td class="table-light">${mascota.nombre_raza}</td>
                    <td class="table-light">${mascota.pelaje}</td>
                    <td class="table-light">${mascota.cedula_cliente} - ${mascota.nombre_cliente}</td>
                    <td class="table-light">${alergiasBadge}</td>
                    <td class="table-light">${enfermedadesBadge}</td>
                    <td class="table-light">${cirugiasBadge}</td>
                    <td class="table-light">
                        <button class="btn btn-sm btn-success btn-actualizar" value="${mascota.id_mascota}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button> 
                        <button class="btn btn-sm btn-danger btn-eliminar" value="${mascota.id_mascota}">Eliminar</button>
                    </td>
                </tr>`;
            });
        } else if(result.status === "error"){
             TablaMascotas.innerHTML = "<tr> <td colspan='14'> Error al obtener los registros</td></tr>";
        }
    });
}

cargarCatalogos().then(obtenerDatos);

btnAgregar.addEventListener("click", function(e){
     formularioMascotas.reset();
     limpiarErroresValidacion();
     selectAlergias.clear(true);
     selectEnfermedades.clear(true);
     selectCirugias.clear(true);

     TituloModal.innerText = "Agregar Nueva Mascota";
     document.getElementById("id_mascota").value = "";
     
    selectAlergias.clear();
    selectEnfermedades.clear();
    selectCirugias.clear();
});

TablaMascotas.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-actualizar")) {
        e.preventDefault(); 
        TituloModal.innerText = "Actualizar Mascota";
        let id = e.target.value;
        let datos = new FormData();

        datos.append("obtenerMascota", true);
        datos.append("id", id);

        fetch(window.location, {method: "post", body: datos})
        .then(respuesta => respuesta.json())
        .then(resultado => {
            let result = resultado.resultado;

            if(resultado.status === "success"){
                document.getElementById("id_mascota").value = result.id_mascota;
                document.getElementById("nombre").value = result.nombre;
                document.getElementById("edad").value = result.edad;
                document.getElementById("sexo").value = result.sexo;
                document.getElementById("fch_nacimiento").value = result.fch_nacimiento;
                document.getElementById("chip").value = result.chip;
                document.getElementById("id_raza").value = result.id_raza;
                document.getElementById("pelaje").value = result.pelaje;
                document.getElementById("cedula_cliente").value = result.cedula_cliente;
                document.getElementById("procedencia").value = result.procedencia;
                cargarAntecedentesEnFormulario(result.id_mascota);

            } else if(resultado.status === "error"){
                Swal.fire({title: "Error", text: "Error al obtener el registro", icon: "error"});
            }
        });
    }
});
function cargarAntecedentesEnFormulario(id) {
    const datos = new FormData();
    datos.append("obtenerAntecedentes", true);
    datos.append("id", id);

    fetch(window.location, {method: "post", body: datos})
        .then(respuesta => respuesta.json())
        .then(resultado => {
            if (resultado.status !== "success") {
                return;
            }
            selectAlergias.setValue(resultado.alergias.map(alergia => String(alergia.id_alergia)));
            selectEnfermedades.setValue(resultado.enfermedades.map(enfermedad => String(enfermedad.id_patologia)));
            selectCirugias.setValue(resultado.cirugias.map(cirugia => String(cirugia.id_cirugia)));
        });
}

formularioMascotas.addEventListener("submit", function(e){
       e.preventDefault();
       limpiarErroresValidacion();
       let datos = new FormData(formularioMascotas);
       let id = document.getElementById("id_mascota").value;

       if(id === ""){
           datos.append("agregar", true);
       } else {
           datos.append("actualizar", true);
           datos.append("id", id);
       }

       fetch(window.location, {method: "post", body: datos})
       .then(respuesta => respuesta.json())
       .then(resultado => {
            if(resultado.status === "success"){
                id === "" ? alertAgregar("success") : alertActualizar("success");
                 
                let modalInstance = bootstrap.Modal.getInstance(document.getElementById("ModalAgregar"));
                modalInstance.hide();

                obtenerDatos();
            } else if(resultado.status === "error"){
                renderizarErroresValidacion(resultado.errors);
                id === "" ? alertAgregar("error") : alertActualizar("error");
            }
       });
});

TablaMascotas.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault(); 
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);
        alertEliminar("post", datos, obtenerDatos);
    }
});

filtrar.addEventListener("input", function(){
      let param = this.value;
      obtenerDatos(param);
});
