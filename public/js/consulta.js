const tablaConsultas = $("#tabla-consultas");
const filtrar = $("#filtrar");
const renderTexto = $.fn.dataTable.render.text();


function normalizarErrorAjax(xhr, estado, error) {
    if (xhr instanceof Error) throw xhr;
    throw new Error(xhr.responseJSON?.mensaje || error || estado || "No fue posible completar la solicitud.");
}

function alertar(opciones) {
    if (window.Swal) return Swal.fire(opciones);
    return Promise.resolve();
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
    observaciones: "Observaciones",
    alergias: "Alergias",
    cirugias_previas: "Cirugías previas",
    enfermedades_sufridas: "Enfermedades sufridas"
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
            elemento.append($("<dd>").addClass("text-break").text(valor));
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
        const contenido = $("<li>").addClass("list-group-item text-break");
        contenido.append($("<strong>").text(item[campoPrincipal]));
        contenido.append($("<span>").text(` — ${item[campoSecundario] ?? ""}`));
        lista.append(contenido);
    });
    return seccion.append(lista);
}

function agregarDetalle(contenedor, etiqueta, valor, clases = "col-md-6 mb-3") {
    const campo = $("<div>").addClass(clases);
    campo.append($("<dt>").text(etiqueta));
    campo.append($("<dd>").addClass("text-break").text(valor ?? "—"));
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
    agregarDetalle(resumen, "Peso", consulta.peso_consulta +' Kg' || "—");
    agregarDetalle(resumen, "Seguimiento", consulta.comentario || "—", "col-12 mb-3");
    cuerpo.append(resumen);
    cuerpo.append(renderizarSeccion("Anamnesis", consulta.detalle?.anamnesis));
    cuerpo.append(renderizarSeccion("Antecedentes de la mascota", consulta.detalle?.antecedentes));
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

const columnasConsultas = [
    { data: "id_consulta", className: "table-light td-nowrap td-left" },
    { data: "nombre_mascota", className: "table-light td-nowrap td-left", render: renderTexto },
    { data: "nombre_cliente", className: "table-light td-nowrap td-left", render: renderTexto },
    { data: "fecha", className: "table-light td-nowrap td-left", render: renderTexto },
    {
        data: "tipo_ingreso",
        className: "table-light td-nowrap td-left",
        render: tipo => Number(tipo) === 1 ? "Primera vez" : "Sucesivo"
    },
    {
        data: "remitido",
        className: "table-light td-nowrap",
        render: remitido => Number(remitido) === 1 ? "Sí" : "No"
    },
    { data: "pronostico", className: "table-light td-large td-left", render: valor => renderTexto.display(valor || "—") },
    { data: "tratamiento_consulta", className: "table-light td-large td-left", render: valor => renderTexto.display(valor || "—") },
    { data: "peso_consulta", className: "table-light td-nowrap td-left", render: valor => renderTexto.display(valor || "—") },
    { data: "comentario", className: "table-light td-large td-left", render: valor => renderTexto.display(valor || "—") }
];

if ($("#detalles").length) {
    columnasConsultas.push({
        data: "modalId",
        className: "table-light td-detalle ",
        orderable: false,
        searchable: false,
        render: modalId => `<button type="button" data-bs-toggle="modal" data-bs-target="#${modalId}" class="btn btn-sm btn-success text-nowrap">Ver detalle</button>`
    });
}

if ($("#acciones-consulta").length) {
    columnasConsultas.push({
        data: "id_consulta",
        className: "table-light td-acciones",
        orderable: false,
        searchable: false,
        render: id => `<div class="consulta-acciones d-flex flex-nowrap gap-1">
            <button type="button" data-bs-toggle="modal" data-bs-target="#ReporteModal" data-pdf-consulta="${id}" class="btn btn-sm btn-success text-nowrap">Generar PDF</button>
            <a href="index.php?url=NuevaConsulta&id_consulta=${encodeURIComponent(id)}" class="btn btn-sm btn-success text-nowrap">Actualizar</a>
            <button type="button" data-eliminar-consulta="${id}" class="btn btn-sm btn-danger text-nowrap">Eliminar</button>
        </div>`
    });
}

const tabla = tablaConsultas.DataTable({
    serverSide: true,
    autoWidth: false,
    scrollX: true,
    searching: true,
    lengthChange: true,
    lengthMenu: [5, 10, 20],
    pageLength: 5,
    layout: { topStart: null, topEnd: null, bottomStart: "pageLength", bottomEnd: "paging" },
    columns: columnasConsultas,
    language: {
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        infoFiltered: "(filtrado de un total de _MAX_ registros)",
        loadingRecords: "Cargando...",
        zeroRecords: "No se encontraron resultados",
        emptyTable: "Todavía no hay consultas registradas.",
        paginate: { first: "Primero", previous: "Anterior", next: "Siguiente", last: "Último" }
    },
    ajax: function (request, callback) {
        const datos = new FormData();
        datos.append("obtener", "true");
        datos.append("draw", request.draw);
        datos.append("start", request.start);
        datos.append("length", request.length);
        datos.append("search[value]", request.search.value);
        const url = tablaConsultas.data("url") || "index.php?url=Consulta";

        peticionAjax(datos, null, () => {}, url)
            .then(resultado => {
                if (!Array.isArray(resultado.data)) {
                    throw new Error("El servidor devolvió un formato de respuesta inválido.");
                }
                $("body > .modal[id^='consulta-detalle-']").remove();
                resultado.data.forEach(consulta => {
                    consulta.modalId = crearDetalle(consulta);
                });
                callback(resultado);
            })
            .catch(normalizarErrorAjax)
            .catch(error => {
                console.error("No se pudieron cargar las consultas:", error);
                alertar({ icon: "error", title: "Error", text: error.message });
                callback({ draw: request.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
            });
    }
});

filtrar.on("input", () => tabla.search(filtrar.val()).draw());

tabla.on("draw.dt", function () {
    tabla.columns.adjust();
});

$(window).on("resize", function () {
    tabla.columns.adjust();
});

const reporteModal = document.getElementById("ReporteModal");
const pdfCanvas = document.getElementById("pdf-canvas");

if (reporteModal && pdfCanvas && window.pdfjsLib) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'public/js/pdfjs/pdf.worker.min.js';
    let pdfUrl = "";

    reporteModal.addEventListener("show.bs.modal", event => {
        const consultaId = event.relatedTarget?.getAttribute("data-pdf-consulta");
        if (!consultaId) return;

        pdfUrl = `index.php?url=Consulta&GenerarPDF=${consultaId}`;
        $("#GenerarPDF").attr({ href: pdfUrl, target: "_blank" });
        pdfCanvas.width = 0;
        pdfCanvas.height = 0;
    });

    reporteModal.addEventListener("shown.bs.modal", async () => {
        if (!pdfUrl) return;

        try {
            const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
            const page = await pdf.getPage(1);
            const viewport = page.getViewport({ scale: 1.2 });
            const context = pdfCanvas.getContext("2d");
            pdfCanvas.width = viewport.width;
            pdfCanvas.height = viewport.height;
            await page.render({ canvasContext: context, viewport }).promise;
        } catch (error) {
            console.error("Error al renderizar el PDF:", error);
            alertar({
                icon: "error",
                title: "Error al cargar la vista previa",
                text: "No se pudo abrir el PDF. Intenta de nuevo o revisa la respuesta del servidor."
            });
        }
    });
} else if (reporteModal && pdfCanvas) {
    console.error("No se pudo inicializar la vista previa: PDF.js no está disponible.");
}

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
                .then(() => tabla.ajax.reload())
                .catch(normalizarErrorAjax)
                .catch(error => {
                    boton.prop("disabled", false);
                    alertar({ icon: "error", title: "Error", text: error.message });
                });
        });
    });


}
