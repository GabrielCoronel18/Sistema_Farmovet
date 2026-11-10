const formularioConsulta = $("[data-consulta-form]");
const tablaConsultas = $("#tabla-consultas");
const catalogos = { patologias: [], medicamentos: [] };
const limite = $("#limite");
const btnAnterior = $("#btn-anterior");
const btnSiguiente = $("#btn-siguiente");
const infoPagina = $("#info-pagina");
const filtrar = $("#filtrar");
let paginaActual = 1;


function normalizarErrorAjax(xhr, estado, error) {
    if (xhr instanceof Error) throw xhr;
    throw new Error(xhr.responseJSON?.mensaje || error || estado || "No fue posible completar la solicitud.");
}

function alertar(opciones) {
    if (window.Swal) return Swal.fire(opciones);
    return Promise.resolve();
}

function mostrarErroresConsulta(errores) {
    Object.entries(errores || {}).forEach(([nombre, mensaje]) => {
        const campo = formularioConsulta.find("[name]").filter(function () {
            return this.name === nombre;
        });
        if (!campo.length) return;
        campo.addClass("is-invalid");
        let error = campo.parent().find(".invalid-feedback").first();
        if (!error.length) {
            error = $("<div>").addClass("invalid-feedback").appendTo(campo.parent());
        }
        error.text(mensaje);
    });
}

function activarTomSelect(select) {
    if (!window.TomSelect || select.tomselect) return;
    new TomSelect(select, {
        create: false,
        allowEmptyOption: true,
        placeholder: select.dataset.placeholder || "Seleccione una opción"
    });
}

function llenarSelect(elemento, opciones, textoInicial) {
    if (elemento.tomselect) elemento.tomselect.destroy();
    const select = $(elemento).empty()
        .append($("<option>").val("").text(textoInicial));
    opciones.forEach(opcion => {
        select.append($("<option>").val(opcion.id).text(opcion.nombre));
    });
    activarTomSelect(elemento);
}

function cargarFila(tipo) {
    const plantilla = $(`#${tipo}-template`)[0];
    const destino = $(tipo === "diagnostico" ? "#diagnosticos-container" : "#recetas-container");
    if (!plantilla || !destino.length) return;

    destino.append(plantilla.content.cloneNode(true));
    const fila = destino.children().last();
    const selector = tipo === "diagnostico"
        ? '[name="id_patologia[]"]'
        : '[name="id_medicamento[]"]';
    const select = fila.find(selector)[0];
    const opciones = tipo === "diagnostico"
        ? catalogos.patologias
        : catalogos.medicamentos;

    if (select) llenarSelect(select, opciones, "Seleccione una opción");
}

function establecerValores(datos) {
    Object.entries(datos || {}).forEach(([campo, valor]) => {
        const elemento = formularioConsulta.find("[name]").filter(function () {
            return this.name === campo;
        }).first();
        if (!elemento.length || Array.isArray(valor)) return;
        if (elemento[0].tomselect) {
            elemento[0].tomselect.setValue(String(valor ?? ""));
        } else {
            elemento.val(valor ?? "");
        }
    });
}

function cargarDiagnosticos(diagnosticos) {
    diagnosticos.forEach((diagnostico, indice) => {
        let filas = $(".diagnostico-row");
        if (indice >= filas.length) cargarFila("diagnostico");
        const fila = $(".diagnostico-row").eq(indice);
        fila.find('[name="id_diagnostico[]"]').val(diagnostico.id_diagnostico);
        fila.find('[name="id_patologia[]"]')[0].tomselect
            ?.setValue(String(diagnostico.id_patologia));
        fila.find('[name="tipo_diagnostico[]"]').val(diagnostico.tipo_diagnostico);
    });
}

function cargarRecetas(recetas) {
    recetas.forEach((receta, indice) => {
        let filas = $(".receta-row");
        if (indice >= filas.length) cargarFila("receta");
        const fila = $(".receta-row").eq(indice);
        fila.find('[name="id_recipe[]"]').val(receta.id_recipe);
        fila.find('[name="id_medicamento[]"]')[0].tomselect
            ?.setValue(String(receta.id_medicamento));
        fila.find('[name="dosis[]"]').val(receta.dosis);
        fila.find('[name="frecuencia[]"]').val(receta.frecuencia);
        fila.find('[name="duracion[]"]').val(receta.duracion);
    });
}

function cargarCatalogos() {
    const datos = new FormData();
    datos.append("obtenerCatalogos", "true");

    return peticionAjax(datos, null, () => {}, formularioConsulta.attr("action"))
        .then(resultado => {
        if (resultado.status !== "success") {
            throw new Error("No se pudieron cargar los catálogos.");
        }

        llenarSelect(
            $("#buscarPaciente")[0],
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

        $('[name="id_patologia[]"]').each((_, select) =>
            llenarSelect(select, catalogos.patologias, "Seleccione una opción")
        );
        $('[name="id_medicamento[]"]').each((_, select) =>
            llenarSelect(select, catalogos.medicamentos, "Seleccione una opción")
        );
        })
        .catch(normalizarErrorAjax);
}

function cargarConsulta(id) {
    const datos = new FormData();
    datos.append("obtenerConsulta", "true");
    datos.append("id_consulta", id);

    return peticionAjax(datos, null, () => {}, formularioConsulta.attr("action"))
        .then(resultado => {
        if (resultado.status !== "success") {
            throw new Error("No fue posible cargar la consulta.");
        }
        const consulta = resultado.resultado;
        establecerValores(consulta);
        establecerValores({comentario_seguimiento: consulta.comentario});
        establecerValores(consulta.anamnesis);
        establecerValores(consulta.examen);
        establecerValores(consulta.laboratorio);
        formularioConsulta.find('[name="id_consulta"]').val(id);
        cargarDiagnosticos(consulta.diagnosticos || []);
        cargarRecetas(consulta.recetas || []);
        })
        .catch(normalizarErrorAjax);
}

function prepararFormulario() {
    const parametros = new URLSearchParams(window.location.search);
    const id = Number(parametros.get("id_consulta") || 0);
    const fecha = formularioConsulta.find('[name="fecha"]');

    if (id > 0) {
        formularioConsulta.find('[name="id_consulta"]').val(id);
    } else if (fecha.length && !fecha.val()) {
        fecha.val(new Date().toISOString().slice(0, 10));
    }

    cargarCatalogos()
        .then(() => id > 0 ? cargarConsulta(id) : null)
        .catch(error => alertar({ icon: "error", title: "Error", text: error.message }));

    formularioConsulta.on("submit", evento => {
        evento.preventDefault();
        if (!formularioConsulta.valid()) return;
        const boton = formularioConsulta.find('button[type="submit"]');
        boton.prop("disabled", true);

        peticionAjax(new FormData(formularioConsulta[0]), null, () => {}, formularioConsulta.attr("action"))
            .then(resultado => {
                if (resultado.status !== "success") {
                    mostrarErroresConsulta(resultado.errors);
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
            .catch(normalizarErrorAjax)
            .catch(error => {
                alertar({ icon: "error", title: "Error", text: error.message });
                boton.prop("disabled", false);
            });
    });
}

$("[data-agregar]").on("click", function () {
    cargarFila(this.dataset.agregar);
});

$(document).on("click", ".quitar-fila", function () {
    $(this).closest(".diagnostico-row, .receta-row").remove();
});

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
    const seccion = $("<section>");
    seccion.append($("<h6>").addClass("border-bottom pb-2 mt-3").text(titulo));
    if (!datos) {
        return seccion.append($("<p>").addClass("text-muted").text("Sin información registrada."));
    }
    const lista = $("<dl>").addClass("row");
    Object.entries(datos)
        .filter(([campo, valor]) => !campo.startsWith("id_") && valor !== null && valor !== "")
        .forEach(([campo, valor]) => {
            const elemento = $("<div>").addClass("col-md-6 mb-2");
            elemento.append($("<dt>").text(obtenerEtiquetaCampo(campo)));
            elemento.append($("<dd>").text(valor));
            lista.append(elemento);
        });
    if (!lista.children().length) {
        return seccion.append($("<p>").addClass("text-muted").text("Sin información registrada."));
    }
    return seccion.append(lista);
}

function renderizarLista(titulo, datos, campoPrincipal, campoSecundario) {
    const seccion = $("<section>");
    seccion.append($("<h6>").addClass("border-bottom pb-2 mt-3").text(titulo));
    if (!datos || !datos.length) {
        return seccion.append($("<p>").addClass("text-muted").text("Sin información registrada."));
    }
    const lista = $("<ul>").addClass("list-group");
    datos.forEach(item => {
        const contenido = $("<li>").addClass("list-group-item");
        contenido.append($("<strong>").text(item[campoPrincipal]));
        contenido.append($("<span>").text(` — ${item[campoSecundario] ?? ""}`));
        lista.append(contenido);
    });
    return seccion.append(lista);
}

function agregarDetalle(contenedor, etiqueta, valor, clases = "col-md-6 mb-3") {
    const campo = $("<div>").addClass(clases);
    campo.append($("<dt>").text(etiqueta));
    campo.append($("<dd>").text(valor ?? "—"));
    contenedor.append(campo);
}

function crearDetalle(consulta) {
    const id = Number(consulta.id_consulta);
    const modalId = `consulta-detalle-${id}`;
    $(`#${modalId}`).remove();
    const modal = $("<div>").addClass("modal fade").attr({ id: modalId, tabindex: "-1" });
    const contenido = $("<div>").addClass("modal-content");
    const encabezado = $("<div>").addClass("modal-header");
    const titulo = $("<div>");
    titulo.append($("<h5>").addClass("modal-title").text(`Consulta #${id}`));
    titulo.append(
        $("<div>").addClass("small text-white-50")
            .text(`${consulta.nombre_mascota ?? ""} · ${consulta.fecha ?? ""}`)
    );
    encabezado.append(titulo);
    encabezado.append(
        $("<button>").attr({
            type: "button",
            "data-bs-dismiss": "modal",
            "aria-label": "Cerrar"
        }).addClass("btn-close btn-close-white")
    );

    const cuerpo = $("<div>").addClass("modal-body");
    const resumen = $("<dl>").addClass("row mb-0");
    agregarDetalle(resumen, "Paciente / responsable",
        `${consulta.nombre_mascota ?? ""} / ${consulta.nombre_cliente ?? ""}`);
    agregarDetalle(resumen, "Fecha y tipo",
        `${consulta.fecha ?? ""} · ${Number(consulta.tipo_ingreso) === 1 ? "Primera vez" : "Sucesivo"}`);
    agregarDetalle(resumen, "Remitido", Number(consulta.remitido) === 1 ? "Sí" : "No");
    agregarDetalle(resumen, "Pronóstico", consulta.pronostico || "—");
    agregarDetalle(resumen, "Tratamiento", consulta.tratamiento_consulta || "—");
    agregarDetalle(resumen, "Peso", consulta.peso_consulta || "—");
    agregarDetalle(resumen, "Seguimiento", consulta.comentario || "—", "col-12 mb-3");
    cuerpo.append(resumen);
    cuerpo.append(renderizarSeccion("Anamnesis", consulta.detalle?.anamnesis));
    cuerpo.append(renderizarSeccion("Examen clínico", consulta.detalle?.examen));
    cuerpo.append(renderizarSeccion("Laboratorio", consulta.detalle?.laboratorio));
    cuerpo.append(renderizarLista("Diagnósticos", consulta.detalle?.diagnosticos, "patologia", "tipo_diagnostico"));
    cuerpo.append(renderizarLista("Recipe", consulta.detalle?.recetas, "nombre_medicamento", "dosis"));

    const pie = $("<div>").addClass("modal-footer").append(
        $("<button>").attr({ type: "button", "data-bs-dismiss": "modal" })
            .addClass("btn btn-secondary").text("Cerrar")
    );
    contenido.append(encabezado, cuerpo, pie);
    modal.append($("<div>").addClass("modal-dialog modal-xl modal-dialog-scrollable").append(contenido));
    $("body").append(modal);
    return modalId;
}

function actualizarPaginacion(hayMas) {
    btnAnterior.parent().toggleClass("disabled", paginaActual === 1);
    btnAnterior.prop("disabled", paginaActual === 1);
    btnSiguiente.prop("disabled", !hayMas);
    infoPagina.text(`Página ${paginaActual}`);
}

function obtenerConsultas(param = null, pagina = paginaActual) {
    const datos = new FormData();
    datos.append("obtener", "true");
    datos.append("pagina", pagina);
    datos.append("limite", limite.val());
    if (param !== null) datos.append("parametro", param);

    const url = tablaConsultas.data("url") || "index.php?url=Consulta";

    peticionAjax(datos, null, () => {}, url).then(resultado => {
        if (resultado.status !== "success") {
            throw new Error("No fue posible cargar las consultas.");
        }
        tablaConsultas.empty();
        (resultado.resultados || []).forEach(consulta => {
            const id = Number(consulta.id_consulta);
            const modalId = crearDetalle(consulta);
            const fila = $("<tr>");
            const agregarCelda = (valor, clases = "table-light") =>
                fila.append($("<td>").addClass(clases).text(valor ?? ""));
            agregarCelda(id);
            agregarCelda(consulta.nombre_mascota);
            agregarCelda(consulta.nombre_cliente);
            agregarCelda(consulta.fecha);
            agregarCelda(Number(consulta.tipo_ingreso) === 1 ? "Primera vez" : "Sucesivo");
            agregarCelda(Number(consulta.remitido) === 1 ? "Sí" : "No");
            agregarCelda(consulta.pronostico || "—", "table-light td-large");
            agregarCelda(consulta.tratamiento_consulta || "—", "table-light td-large");
            agregarCelda(consulta.peso_consulta || "—");
            agregarCelda(consulta.comentario || "—", "table-light td-large");

            const verDetalle = $("<button>").attr({
                type: "button",
                "data-bs-toggle": "modal",
                "data-bs-target": `#${modalId}`
            }).addClass("btn btn-sm btn-success").text("Ver detalle");
            fila.append($("<td>").addClass("table-light").append(verDetalle));
            const actualizar = $("<a>").attr("href", `index.php?url=NuevaConsulta&id_consulta=${id}`)
                .addClass("btn btn-sm btn-success").text("Actualizar");
            const eliminar = $("<button>").attr({
                type: "button",
                "data-eliminar-consulta": id
            }).addClass("btn btn-sm btn-danger").text("Eliminar");
            fila.append($("<td>").addClass("table-light text-nowrap").append(actualizar, " ", eliminar));
            tablaConsultas.append(fila);
        });
        if (!tablaConsultas.children().length) {
            if (pagina > 1) {
                obtenerConsultas(param, pagina - 1);
                return;
            }
            tablaConsultas.append(
                $("<tr>").append(
                    $("<td>").attr("colspan", 12).addClass("text-center text-muted py-4")
                        .text("Todavía no hay consultas registradas.")
                )
            );
        }
        paginaActual = pagina;
        actualizarPaginacion(resultado.hayMas);
    }).catch(normalizarErrorAjax)
        .catch(error => alertar({ icon: "error", title: "Error", text: error.message }));
}

if (formularioConsulta.length) prepararFormulario();

if (tablaConsultas.length) {
    tablaConsultas.on("click", "[data-eliminar-consulta]", function () {
        const boton = $(this);
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
            datos.append("id", boton.attr("data-eliminar-consulta"));
            boton.prop("disabled", true);
            peticionAjax(datos, null, () => {}, tablaConsultas.data("url") || "index.php?url=Consulta")
                .then(resultado => {
                    if (resultado.status !== "success") {
                        throw new Error("No fue posible eliminar la consulta.");
                    }
                    return alertar({ icon: "success", title: "Consulta eliminada" });
                })
                .then(() => obtenerConsultas(filtrar.val(), paginaActual))
                .catch(normalizarErrorAjax)
                .catch(error => {
                    boton.prop("disabled", false);
                    alertar({ icon: "error", title: "Error", text: error.message });
                });
        });
    });
    obtenerConsultas();

    filtrar.on("input", () => {
        paginaActual = 1;
        obtenerConsultas(filtrar.val(), paginaActual);
    });

    limite.on("change", () => {
        paginaActual = 1;
        obtenerConsultas(filtrar.val(), paginaActual);
    });

    btnAnterior.on("click", () => {
        if (paginaActual > 1) obtenerConsultas(filtrar.val(), paginaActual - 1);
    });

    btnSiguiente.on("click", () => {
        if (!btnSiguiente.prop("disabled")) obtenerConsultas(filtrar.val(), paginaActual + 1);
    });

}
