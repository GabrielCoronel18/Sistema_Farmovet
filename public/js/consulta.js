const formularioConsulta = document.querySelector("[data-consulta-form]");
const tablaConsultas = document.getElementById("tabla-consultas");
const catalogos = { patologias: [], medicamentos: [] };
const limite = document.getElementById("limite");
const btnAnterior = document.getElementById("btn-anterior");
const btnSiguiente = document.getElementById("btn-siguiente");
const infoPagina = document.getElementById("info-pagina");
const filtrar = document.getElementById("filtrar");
let paginaActual = 1;


function solicitar(datos, url = window.location) {
    return fetch(url, { method: "POST", body: datos })
        .then(respuesta => respuesta.json());
}

function alertar(opciones) {
    if (window.Swal) return Swal.fire(opciones);
    return Promise.resolve();
}

function activarTomSelect(select) {
    if (!window.TomSelect || select.tomselect) return;
    new TomSelect(select, {
        create: false,
        allowEmptyOption: true,
        placeholder: select.dataset.placeholder || "Seleccione una opción"
    });
}

function llenarSelect(select, opciones, textoInicial) {
    if (select.tomselect) select.tomselect.destroy();
    select.innerHTML = `<option value="">${textoInicial}</option>`;
    opciones.forEach(opcion => {
        select.add(new Option(opcion.nombre, opcion.id));
    });
    activarTomSelect(select);
}

function cargarFila(tipo) {
    const plantilla = document.getElementById(`${tipo}-template`);
    const destino = document.getElementById(
        tipo === "diagnostico" ? "diagnosticos-container" : "recetas-container"
    );
    if (!plantilla || !destino) return;

    destino.appendChild(plantilla.content.cloneNode(true));
    const fila = destino.lastElementChild;
    const selector = tipo === "diagnostico"
        ? '[name="id_patologia[]"]'
        : '[name="id_medicamento[]"]';
    const select = fila.querySelector(selector);
    const opciones = tipo === "diagnostico"
        ? catalogos.patologias
        : catalogos.medicamentos;

    if (select) llenarSelect(select, opciones, "Seleccione una opción");
}

function establecerValores(datos) {
    Object.entries(datos || {}).forEach(([campo, valor]) => {
        const elemento = formularioConsulta.querySelector(`[name="${campo}"]`);
        if (!elemento || Array.isArray(valor)) return;
        if (elemento.tomselect) {
            elemento.tomselect.setValue(String(valor ?? ""));
        } else {
            elemento.value = valor ?? "";
        }
    });
}

function cargarDiagnosticos(diagnosticos) {
    const filas = document.querySelectorAll(".diagnostico-row");
    diagnosticos.forEach((diagnostico, indice) => {
        if (indice >= filas.length) cargarFila("diagnostico");
        const fila = document.querySelectorAll(".diagnostico-row")[indice];
        fila.querySelector('[name="id_diagnostico[]"]').value = diagnostico.id_diagnostico;
        fila.querySelector('[name="id_patologia[]"]').tomselect
            ?.setValue(String(diagnostico.id_patologia));
        fila.querySelector('[name="tipo_diagnostico[]"]').value = diagnostico.tipo_diagnostico;
    });
}

function cargarRecetas(recetas) {
    const filas = document.querySelectorAll(".receta-row");
    recetas.forEach((receta, indice) => {
        if (indice >= filas.length) cargarFila("receta");
        const fila = document.querySelectorAll(".receta-row")[indice];
        fila.querySelector('[name="id_recipe[]"]').value = receta.id_recipe;
        fila.querySelector('[name="id_medicamento[]"]').tomselect
            ?.setValue(String(receta.id_medicamento));
        fila.querySelector('[name="dosis[]"]').value = receta.dosis;
        fila.querySelector('[name="frecuencia[]"]').value = receta.frecuencia;
        fila.querySelector('[name="duracion[]"]').value = receta.duracion;
    });
}

function cargarCatalogos() {
    const datos = new FormData();
    datos.append("obtenerCatalogos", "true");

    return solicitar(datos, formularioConsulta.action).then(resultado => {
        if (resultado.status !== "success") {
            throw new Error("No se pudieron cargar los catálogos.");
        }

        llenarSelect(
            document.getElementById("buscarPaciente"),
            resultado.mascotas.map(mascota => ({
                id: mascota.id_mascota,
                nombre: `${mascota.nombre} - ${mascota.nombre_cliente}`
            })),
            "Seleccione una mascota"
        );
        catalogos.patologias = resultado.patologias.map(patologia => ({
            id: patologia.id_patologia,
            nombre: patologia.nombre
        }));
        catalogos.medicamentos = resultado.medicamentos.map(medicamento => ({
            id: medicamento.id_medicamento,
            nombre: medicamento.nombre_medicamento
        }));

        document.querySelectorAll('[name="id_patologia[]"]').forEach(select =>
            llenarSelect(select, catalogos.patologias, "Seleccione una opción")
        );
        document.querySelectorAll('[name="id_medicamento[]"]').forEach(select =>
            llenarSelect(select, catalogos.medicamentos, "Seleccione una opción")
        );
    });
}

function cargarConsulta(id) {
    const datos = new FormData();
    datos.append("obtenerConsulta", "true");
    datos.append("id_consulta", id);

    return solicitar(datos, formularioConsulta.action).then(resultado => {
        if (resultado.status !== "success") {
            throw new Error("No fue posible cargar la consulta.");
        }
        const consulta = resultado.resultado;
        establecerValores(consulta);
        establecerValores({comentario_seguimiento: consulta.comentario});
        establecerValores(consulta.anamnesis);
        establecerValores(consulta.examen);
        establecerValores(consulta.laboratorio);
        formularioConsulta.querySelector('[name="id_consulta"]').value = id;
        cargarDiagnosticos(consulta.diagnosticos || []);
        cargarRecetas(consulta.recetas || []);
    });
}

function prepararFormulario() {
    const parametros = new URLSearchParams(window.location.search);
    const id = Number(parametros.get("id_consulta") || 0);
    const fecha = formularioConsulta.querySelector('[name="fecha"]');

    if (id > 0) {
        formularioConsulta.querySelector('[name="id_consulta"]').value = id;
    } else if (fecha && !fecha.value) {
        fecha.value = new Date().toISOString().slice(0, 10);
    }

    cargarCatalogos()
        .then(() => id > 0 ? cargarConsulta(id) : null)
        .catch(error => alertar({ icon: "error", title: "Error", text: error.message }));

    formularioConsulta.addEventListener("submit", evento => {
        evento.preventDefault();
        const boton = formularioConsulta.querySelector('button[type="submit"]');
        if (boton) boton.disabled = true;

        solicitar(new FormData(formularioConsulta), formularioConsulta.action)
            .then(resultado => {
                if (resultado.status !== "success") {
                    throw new Error(resultado.mensaje || "No fue posible guardar la consulta.");
                }
                return alertar({
                    icon: "success",
                    title: "Consulta guardada",
                    text: resultado.mensaje || "La consulta fue guardada."
                }).then(() => {
                    window.location.href = resultado.redirect || "index.php?url=Consulta";
                });
            })
            .catch(error => {
                alertar({ icon: "error", title: "Error", text: error.message });
                if (boton) boton.disabled = false;
            });
    });
}

document.querySelectorAll("[data-agregar]").forEach(boton => {
    boton.addEventListener("click", () => cargarFila(boton.dataset.agregar));
});

document.addEventListener("click", evento => {
    const boton = evento.target.closest(".quitar-fila");
    if (boton) boton.closest(".diagnostico-row, .receta-row")?.remove();
});

function escapar(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, caracter => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
    })[caracter]);
}

const etiquetasCampos = {
    motivo_consulta: "Motivo de consulta",
    inicio_enfermedad: "Inicio de la enfermedad",
    examenes_efectuados: "Exámenes efectuados",
    tratamientos_realizados: "Tratamientos realizados",
    ult_desp_interna: "Última desparasitación interna",
    ult_desp_int_producto: "Producto interno",
    ult_desp_externa: "Última desparasitación externa",
    ult_desp_ext_producto: "Producto externo",
    ultima_vacunacion: "Última vacunación",
    ant_ectoparasitos: "Antecedente de ectoparásitos",
    act_ectoparasitos: "Ectoparásitos actualmente",
    tipo_alimentacion: "Tipo de alimentación",
    frec_alimentacion: "Frecuencia de alimentación",
    apetito: "Apetito",
    ingesta_agua: "Ingesta de agua",
    contacto_animal: "Contacto con animales",
    vomito: "Vómito",
    heces: "Heces",
    miccion: "Micción",
    fch_ultimo_celo: "Fecha del último celo",
    prod_higiene: "Producto de higiene",
    frec_higiene: "Frecuencia de higiene",
    int_ambiente: "Ambiente interno",
    ext_ambiente: "Ambiente externo",
    peso: "Peso",
    cc: "Condición corporal",
    temp_celsius: "Temperatura",
    pulso_yugular: "Pulso yugular",
    fr_rpm: "Frecuencia respiratoria",
    fc_lpm: "Frecuencia cardíaca",
    pulso_ppm: "Pulso periférico",
    tlc_seg: "Tiempo de llenado capilar",
    tpc_seg: "Tiempo de perfusión capilar",
    pas: "Presión arterial sistólica",
    pad: "Presión arterial diastólica",
    prcnt_deshidratacion: "Porcentaje de deshidratación",
    gangliios_palpables: "Ganglios palpables",
    mucosas_visibles: "Mucosas visibles",
    ectoparasitos: "Ectoparásitos",
    actitud: "Actitud",
    hallazgos: "Hallazgos",
    hematologia_completa: "Hematología completa",
    coprologia: "Coprología",
    quimica_sanguinea: "Química sanguínea",
    uro_sangre: "Orina: sangre",
    uro_urob: "Orina: urobilinógeno",
    uro_bli: "Orina: bilirrubina",
    uro_prot: "Orina: proteínas",
    uro_nitritos: "Orina: nitritos",
    uro_cetona: "Orina: cetonas",
    uro_glucosa: "Orina: glucosa",
    uro_ph: "Orina: pH",
    uro_leu: "Orina: leucocitos",
    uro_densidad: "Orina: densidad",
    uro_microorganismos: "Orina: microorganismos",
    uro_celulas: "Orina: células",
    uro_cilindros: "Orina: cilindros",
    uro_cristales: "Orina: cristales",
    descarte: "Pruebas de descarte",
    piel_otros: "Piel / otros",
    snap: "Prueba SNAP",
    observaciones: "Observaciones"
};

function obtenerEtiquetaCampo(campo) {
    return etiquetasCampos[campo] || campo
        .replaceAll("_", " ")
        .replace(/\b\w/g, letra => letra.toUpperCase());
}

function renderizarSeccion(titulo, datos) {
    if (!datos) {
        return `<h6 class="border-bottom pb-2 mt-3">${titulo}</h6>
            <p class="text-muted">Sin información registrada.</p>`;
    }
    const contenido = Object.entries(datos)
        .filter(([campo, valor]) => !campo.startsWith("id_") && valor !== null && valor !== "")
        .map(([campo, valor]) =>
            `<div class="col-md-6 mb-2"><dt>${escapar(obtenerEtiquetaCampo(campo))}</dt>
            <dd>${escapar(valor)}</dd></div>`
        ).join("");
    return `<h6 class="border-bottom pb-2 mt-3">${titulo}</h6>
        <dl class="row">${contenido || '<p class="text-muted">Sin información registrada.</p>'}</dl>`;
}

function renderizarLista(titulo, datos, campoPrincipal, campoSecundario) {
    const contenido = (datos || []).map(item =>
        `<li class="list-group-item"><strong>${escapar(item[campoPrincipal])}</strong>
        — ${escapar(item[campoSecundario])}</li>`
    ).join("");
    return `<h6 class="border-bottom pb-2 mt-3">${titulo}</h6>${
        contenido ? `<ul class="list-group">${contenido}</ul>` :
        '<p class="text-muted">Sin información registrada.</p>'
    }`;
}

function crearDetalle(consulta) {
    const id = Number(consulta.id_consulta);
    const modalId = `consulta-detalle-${id}`;
    document.getElementById(modalId)?.remove();
    const modal = document.createElement("div");
    modal.className = "modal fade";
    modal.id = modalId;
    modal.tabIndex = -1;
    modal.innerHTML = `<div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><div><h5 class="modal-title">Consulta #${id}</h5>
        <div class="small text-white-50">${escapar(consulta.nombre_mascota)} · ${escapar(consulta.fecha)}</div></div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><dl class="row mb-0">
        <div class="col-md-6 mb-3"><dt>Paciente / responsable</dt>
        <dd>${escapar(consulta.nombre_mascota)} / ${escapar(consulta.nombre_cliente)}</dd></div>
        <div class="col-md-6 mb-3"><dt>Fecha y tipo</dt>
        <dd>${escapar(consulta.fecha)} · ${Number(consulta.tipo_ingreso) === 1 ? "Primera vez" : "Sucesivo"}</dd></div>
        <div class="col-md-6 mb-3"><dt>Remitido</dt><dd>${Number(consulta.remitido) === 1 ? "Sí" : "No"}</dd></div>
        <div class="col-md-6 mb-3"><dt>Pronóstico</dt><dd>${escapar(consulta.pronostico || "—")}</dd></div>
        <div class="col-md-6 mb-3"><dt>Tratamiento</dt><dd>${escapar(consulta.tratamiento_consulta || "—")}</dd></div>
        <div class="col-md-6 mb-3"><dt>Peso</dt><dd>${escapar(consulta.peso_consulta || "—")}</dd></div>
        <div class="col-12 mb-3"><dt>Seguimiento</dt><dd>${escapar(consulta.comentario || "—")}</dd></div>
        </dl>${renderizarSeccion("Anamnesis", consulta.detalle?.anamnesis)}
        ${renderizarSeccion("Examen clínico", consulta.detalle?.examen)}
        ${renderizarSeccion("Laboratorio", consulta.detalle?.laboratorio)}
        ${renderizarLista("Diagnósticos", consulta.detalle?.diagnosticos, "patologia", "tipo_diagnostico")}
        ${renderizarLista("Recipe", consulta.detalle?.recetas, "nombre_medicamento", "dosis")}
        </div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        Cerrar</button></div></div></div>`;
    document.body.appendChild(modal);
    return modalId;
}

function actualizarPaginacion(hayMas) {
	btnAnterior.parentElement.classList.toggle("disabled", paginaActual === 1);
	btnAnterior.disabled = paginaActual === 1;
	btnSiguiente.disabled = !hayMas;
	infoPagina.innerText = `Página ${paginaActual}`;
}

function obtenerConsultas(param = null, pagina = paginaActual) {
    const datos = new FormData();
    datos.append("obtener", "true");
    datos.append("pagina", pagina);
	datos.append("limite", limite.value);
	if (param !== null) datos.append("parametro", param);
    
    const url = tablaConsultas.dataset.url || "index.php?url=Consulta";

    solicitar(datos, url).then(resultado => {
        if (resultado.status !== "success") {
            throw new Error("No fue posible cargar las consultas.");
        }
        tablaConsultas.replaceChildren();
        (resultado.resultados || []).forEach(consulta => {
            const id = Number(consulta.id_consulta);
            const modalId = crearDetalle(consulta);
            const fila = document.createElement("tr");
            fila.innerHTML = `<td class="table-light">${id}</td><td class="table-light">${escapar(consulta.nombre_mascota)}</td>
                <td class="table-light">${escapar(consulta.nombre_cliente)}</td><td class="table-light">${escapar(consulta.fecha)}</td>
                <td class="table-light">${Number(consulta.tipo_ingreso) === 1 ? "Primera vez" : "Sucesivo"}</td>
                <td class="table-light">${Number(consulta.remitido) === 1 ? "Sí" : "No"}</td>
                <td class="table-light td-large">${escapar(consulta.pronostico || "—")}</td>
                <td class="table-light td-large">${escapar(consulta.tratamiento_consulta || "—")}</td>
                <td class="table-light">${escapar(consulta.peso_consulta || "—")}</td>
                <td class="table-light td-large">${escapar(consulta.comentario || "—")}</td>
                <td class="table-light"><button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                    data-bs-target="#${modalId}">Ver detalle</button></td>
                <td class="table-light text-nowrap"><a href="index.php?url=NuevaConsulta&id_consulta=${id}"
                    class="btn btn-sm btn-success">Actualizar</a>
                    <button type="button" class="btn btn-sm btn-danger"
                    data-eliminar-consulta="${id}">Eliminar</button></td>`;
            tablaConsultas.appendChild(fila);
            
        });
        if (!tablaConsultas.children.length) {
            if (pagina > 1) {
                obtenerConsultas(param, pagina - 1);
                return;
            }
            tablaConsultas.innerHTML = '<tr><td colspan="12" class="text-center text-muted py-4">' +
                "Todavía no hay consultas registradas.</td></tr>";
        }
        paginaActual = pagina;
        actualizarPaginacion(resultado.hayMas);
    }).catch(error => alertar({ icon: "error", title: "Error", text: error.message }));
}

if (formularioConsulta) prepararFormulario();

if (tablaConsultas) {
    tablaConsultas.addEventListener("click", evento => {
        const boton = evento.target.closest("[data-eliminar-consulta]");
        if (!boton) return;
        alertar({
            icon: "warning",
            title: "¿Eliminar consulta?",
            text: "La consulta dejará de aparecer en el historial.",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#dc3545"
        }).then(confirmacion => {
            if (!confirmacion.isConfirmed) return;
            const datos = new FormData();
            datos.append("eliminar", "true");
            datos.append("id", boton.dataset.eliminarConsulta);
            boton.disabled = true;
            solicitar(datos, tablaConsultas.dataset.url || "index.php?url=Consulta")
                .then(resultado => {
                    if (resultado.status !== "success") {
                        throw new Error("No fue posible eliminar la consulta.");
                    }
                    return alertar({ icon: "success", title: "Consulta eliminada" });
                })
                .then(() => obtenerConsultas(filtrar.value, paginaActual))
                .catch(error => {
                    boton.disabled = false;
                    alertar({ icon: "error", title: "Error", text: error.message });
                });
        });
    });
    obtenerConsultas();

    filtrar.addEventListener("input", () => {
	paginaActual = 1;
	obtenerConsultas(filtrar.value, paginaActual);
});

limite.addEventListener("change", () => {
	paginaActual = 1;
	obtenerConsultas(filtrar.value, paginaActual);
});

btnAnterior.addEventListener("click", () => {
	if (paginaActual > 1) obtenerConsultas(filtrar.value, paginaActual - 1);
});

btnSiguiente.addEventListener("click", () => {
	if (!btnSiguiente.disabled) obtenerConsultas(filtrar.value, paginaActual + 1);
});

}
