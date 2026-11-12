const formularioConsulta = $("[data-consulta-form]");
const catalogos = { patologias: [], medicamentos: [] };

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

if (formularioConsulta.length) prepararFormulario();
