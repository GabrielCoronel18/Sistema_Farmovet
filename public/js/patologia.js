let formularioPatologia = document.querySelector(".PatologiaForm");
let TablaPatologias = document.getElementById("TablaPatologias");
let TituloModal = document.getElementById("TituloModalPatologia");
let btnAgregar = document.getElementById("btnAgregar");
let filtrar = document.getElementById("filtrar");

function obtenerDatos(param = null) {
    let datos = new FormData();
    datos.append("obtener", true);

    if (param != null) {
        datos.append("parametro", param);
    }

    fetch(window.location, { method: "post", body: datos })
        .then(resultados => resultados.json())
        .then(result => {
            if (result.status === "success") {
                TablaPatologias.innerHTML = "";

                result.resultados.forEach(function (patologia) {
                    TablaPatologias.innerHTML +=
                        `<tr>
                            <td class="table-light">${patologia.id_patologia}</td>
                            <td class="table-light">${patologia.nombre}</td>
                            <td class="table-light">${patologia.tipo}</td>
                            <td class="table-light td-large">${patologia.sintomas}</td>
                            <td class="table-light">
                                <button class="btn btn-sm btn-success btn-actualizar" value="${patologia.id_patologia}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
                                <button class="btn btn-sm btn-danger btn-eliminar" value="${patologia.id_patologia}">Eliminar</button>
                            </td>
                        </tr>`;
                });
            } else if (result.status === "error") {
                TablaPatologias.innerHTML = "<tr><td colspan='5'>Error al obtener los registros</td></tr>";
            }
        });
}

obtenerDatos();

btnAgregar.addEventListener("click", function (e) {
    formularioPatologia.reset();
    TituloModal.innerText = "Agregar Nueva Patología";
    document.getElementById("id_patologia").value = "";
});

TablaPatologias.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-actualizar")) {
        e.preventDefault();
        TituloModal.innerText = "Actualizar Patología";
        let id = e.target.value;
        let datos = new FormData();
        datos.append("obtenerPatologia", true);
        datos.append("id", id);

        fetch(window.location, { method: "post", body: datos })
            .then(respuesta => respuesta.json())
            .then(resultado => {
                let result = resultado.resultado;
                if (resultado.status === "success") {
                    document.getElementById("id_patologia").value = result.id_patologia;
                    document.getElementById("nombre").value = result.nombre;
                    document.getElementById("tipo").value = result.tipo;
                    document.getElementById("sintomas").value = result.sintomas;
                } else if (resultado.status === "error") {
                    Swal.fire({ title: "Error", text: "Error al obtener el registro", icon: "error" });
                }
            });
    }
});

formularioPatologia.addEventListener("submit", function (e) {
    e.preventDefault();
    let datos = new FormData(formularioPatologia);
    let id = document.getElementById("id_patologia").value;

    if (id === "") {
        datos.append("agregar", true);
    } else {
        datos.append("actualizar", true);
        datos.append("id", id);
    }

    fetch(window.location, { method: "post", body: datos })
        .then(respuesta => respuesta.json())
        .then(resultado => {
            if (resultado.status === "success") {
                id === "" ? alertAgregar("success") : alertActualizar("success");
                let ModalAgregar = bootstrap.Modal.getInstance(document.getElementById("ModalAgregar"));
                ModalAgregar.hide();
                obtenerDatos();
            } else if (resultado.status === "error") {
                id === "" ? alertAgregar("error") : alertActualizar("error");
            }
        });
});

TablaPatologias.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-eliminar")) {
        e.preventDefault();
        let id = e.target.value;
        let datos = new FormData();
        datos.append("eliminar", true);
        datos.append("id", id);
        alertEliminar("post", datos, obtenerDatos);
    }
});

filtrar.addEventListener("input", function () {
    let param = this.value;
    obtenerDatos(param);
});