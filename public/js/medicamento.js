const formularioMedicamento = $(".MedicamentoForm");
const tablaMedicamentos = $("#TablaMedicamentos");
const tituloModal = $("#TituloModalMedicamento");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const selectTipoMedicamento = $("#tipo");
const selectPresentacion = $("#presentacion");
const limite = $("#limite");
const btnAnterior = $("#btn-anterior");
const btnSiguiente = $("#btn-siguiente");
const infoPagina = $("#info-pagina");
const campoNombre = $("#nombre");
const campoEstado = $("#estado_medicamento");
const errorNombre = $("#error-nombre");
const errorTipo = $("#error-tipo");
const errorPresentacion = $("#error-presentacion");
const errorEstado = $("#error-estado");
let paginaActual = 1;

function cargarSelect(select, opciones, id, nombre, textoInicial) {
	select.empty().append($("<option>").val("").text(textoInicial));
	opciones.forEach(opcion => {
		select.append($("<option>").val(opcion[id]).text(opcion[nombre]));
	});
}

function cargarCatalogos() {
	const tipos = new FormData();
	tipos.append("obtenerTipos", "true");
	const presentaciones = new FormData();
	presentaciones.append("obtenerPresentaciones", "true");

	return Promise.all([peticionAjax(tipos), peticionAjax(presentaciones)])
		.then(([resultadoTipos, resultadoPresentaciones]) => {
			if (resultadoTipos.status !== "success" || resultadoPresentaciones.status !== "success") {
				throw new Error("No se pudieron cargar los catálogos");
			}
			cargarSelect(selectTipoMedicamento, resultadoTipos.resultado, "id_tipo_medicamento", "nom_tipo_medicamento", "Seleccione un tipo");
			cargarSelect(selectPresentacion, resultadoPresentaciones.resultado, "id_presentacion", "nombre_presentacion", "Seleccione una presentación");
		});
}

function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", "true");
	datos.append("pagina", pagina);
	datos.append("limite", limite.value);
	if (param !== null) datos.append("parametro", param);

	peticionAjax(datos).then(resultado => {
		tablaMedicamentos.empty();
		if (resultado.status !== "success") {
			tablaMedicamentos.append(
				$("<tr>").append($("<td>").attr("colspan", 6).text("No hay medicamentos registrados"))
			);
			paginaActual = pagina;
			actualizarPaginacion(false);
			return;
		}
		if (resultado.resultados.length === 0 && pagina > 1) {
			obtenerDatos(param, pagina - 1);
			return;
		}

		resultado.resultados.forEach(medicamento => {
			const id = medicamento.id_medicamento;
			const fila = $("<tr>");
			fila.append($("<td>").addClass("table-light").text(id));
			fila.append($("<td>").addClass("table-light").text(medicamento.nombre_medicamento));
			fila.append($("<td>").addClass("table-light").text(medicamento.nom_tipo_medicamento));
			fila.append($("<td>").addClass("table-light").text(medicamento.nombre_presentacion));
			fila.append(
				$("<td>").addClass("table-light").append(
					$("<span>")
						.addClass(medicamento.estado_medicamento == 1 ? "badge bg-success" : "badge bg-secondary")
						.text(medicamento.estado_medicamento == 1 ? "Activo" : "Inactivo")
				)
			);

			const actualizar = $("<button>")
				.addClass("btn btn-sm btn-success btn-actualizar")
				.val(id)
				.attr("data-bs-toggle", "modal")
				.attr("data-bs-target", "#ModalAgregar")
				.text("Actualizar");
			const eliminar = $("<button>")
				.addClass("btn btn-sm btn-danger btn-eliminar")
				.val(id)
				.text("Eliminar");

			if ($("#acciones").length) {
			fila.append($("<td>").addClass("table-light").append(actualizar, " ", eliminar))
			}
			tablaMedicamentos.append(fila);
		});
		paginaActual = pagina;
		actualizarPaginacion(resultado.hayMas);
	});
}
function actualizarPaginacion(hayMas) {
	btnAnterior.parent().toggleClass("disabled", paginaActual === 1);
	btnAnterior.prop("disabled", paginaActual === 1);
	btnSiguiente.prop("disabled", !hayMas);
	infoPagina.text(`Página ${paginaActual}`);
}


cargarCatalogos().catch(() => {
	Swal.fire({ title: "Error", text: "No se pudieron cargar los catálogos", icon: "error" });
});


obtenerDatos();

btnAgregar.on("click", () => {
	formularioMedicamento[0].reset();
	$("#id_medicamento").val("");
	tituloModal.text("Agregar Nuevo Medicamento");
});

tablaMedicamentos.on("click", ".btn-actualizar", function () {
	const datos = new FormData();
	datos.append("obtenerMedicamento", "true");
	datos.append("id", $(this).val());

	peticionAjax(datos).then(resultado => {
		if (resultado.status !== "success") return;
		const medicamento = resultado.resultado;
		$("#id_medicamento").val(medicamento.id_medicamento);
		campoNombre.val(medicamento.nombre_medicamento);
		selectTipoMedicamento.val(medicamento.id_tipo_medicamento);
		selectPresentacion.val(medicamento.id_presentacion);
		campoEstado.val(medicamento.estado_medicamento);
		tituloModal.text("Actualizar Medicamento");
	});
});

tablaMedicamentos.on("click", ".btn-eliminar", function () {
	const datos = new FormData();
	datos.append("eliminar", "true");
	datos.append("id", $(this).val());
	alertEliminar("post", datos, () => obtenerDatos(filtrar.val(), paginaActual));
});

formularioMedicamento.on("submit", event => {
	event.preventDefault();
	if (!formularioMedicamento.valid()) return;
	const datos = new FormData(formularioMedicamento[0]);
	const id = $("#id_medicamento").val();
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	peticionAjax(datos).then(resultado => {
		if (resultado.status !== "success") {
			if (resultado.errors) {
				if (resultado.errors.nombre) {
					campoNombre.addClass("is-invalid");
					errorNombre.text(resultado.errors.nombre);
				}
				if (resultado.errors.tipo) {
					selectTipoMedicamento.addClass("is-invalid");
					errorTipo.text(resultado.errors.tipo);
				}
				if (resultado.errors.presentacion) {
					selectPresentacion.addClass("is-invalid");
					errorPresentacion.text(resultado.errors.presentacion);
				}
				if (resultado.errors.estado) {
					campoEstado.addClass("is-invalid");
					errorEstado.text(resultado.errors.estado);
				}
			}
			Swal.fire({ title: "Error", text: "No se pudo guardar el medicamento", icon: "error" });
			return;
		}
		id === "" ? alertAgregar("success") : alertActualizar("success");
		bootstrap.Modal.getInstance($("#ModalAgregar")[0]).hide();
		obtenerDatos(filtrar.val(), paginaActual);
	});
});

filtrar.on("input", () => {
	paginaActual = 1;
	obtenerDatos(filtrar.val(), paginaActual);
});

limite.on("change", () => {
	paginaActual = 1;
	obtenerDatos(filtrar.val(), paginaActual);
});

btnAnterior.on("click", () => {
	if (paginaActual > 1) obtenerDatos(filtrar.val(), paginaActual - 1);
});

btnSiguiente.on("click", () => {
	if (!btnSiguiente.prop("disabled")) obtenerDatos(filtrar.val(), paginaActual + 1);
});
