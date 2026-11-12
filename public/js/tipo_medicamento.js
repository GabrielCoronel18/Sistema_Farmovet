const formularioTipoMedicamento = $(".TipoMedicamentoForm");
const tablaTipoMedicamentos = $("#TablaTipoMedicamentos");
const tituloModal = $("#TituloModalTipoMedicamento");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const limite = $("#limite");
const btnAnterior = $("#btn-anterior");
const btnSiguiente = $("#btn-siguiente");
const infoPagina = $("#info-pagina");
const campoNombre = $("#nombre");
const errorNombre = $("#error-nombre");
const campoEstado = $("#estado_tipo_medicamento");
const errorEstado = $("#error-estado_tipo_medicamento");
let paginaActual = 1;

function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", "true");
	datos.append("pagina", pagina);
	datos.append("limite", limite.val());
	if (param !== null) datos.append("parametro", param);

	peticionAjax(datos).then(resultado => {
		tablaTipoMedicamentos.empty();
		if (resultado.status !== "success") {
			tablaTipoMedicamentos.append(
				$("<tr>").append($("<td>").attr("colspan", 4).text("No hay tipos de medicamentos registrados"))
			);
			paginaActual = pagina;
			actualizarPaginacion(false);
			return;
		}
		if (resultado.resultados.length === 0 && pagina > 1) {
			obtenerDatos(param, pagina - 1);
			return;
		}

		resultado.resultados.forEach(tipoMedicamento => {
			const fila = $("<tr>");
			fila.append($("<td>").addClass("table-light").text(tipoMedicamento.id_tipo_medicamento));
			fila.append($("<td>").addClass("table-light").text(tipoMedicamento.nom_tipo_medicamento));
			fila.append(
				$("<td>").addClass("table-light").append(
					$("<span>")
						.addClass(tipoMedicamento.estado == 1 ? "badge bg-success" : "badge bg-secondary")
						.text(tipoMedicamento.estado == 1 ? "Activo" : "Inactivo")
				)
			);
			const actualizar = $("<button>").addClass("btn btn-sm btn-success btn-actualizar")
				.val(tipoMedicamento.id_tipo_medicamento).attr("data-bs-toggle", "modal")
				.attr("data-bs-target", "#ModalAgregar").text("Actualizar");
			const eliminar = $("<button>").addClass("btn btn-sm btn-danger btn-eliminar")
				.val(tipoMedicamento.id_tipo_medicamento).text("Eliminar");
			
			if ($("#acciones").length) {
			fila.append($("<td>").addClass("table-light").append(actualizar, " ", eliminar));
			}
			tablaTipoMedicamentos.append(fila);
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

obtenerDatos();

btnAgregar.on("click", () => {
	formularioTipoMedicamento[0].reset();
	$("#id_tipo_medicamento").val("");
	tituloModal.text("Agregar Nuevo Tipo de Medicamento");
});

tablaTipoMedicamentos.on("click", ".btn-actualizar", function () {
	const datos = new FormData();
	datos.append("obtenerTipoMedicamento", "true");
	datos.append("id", $(this).val());

	peticionAjax(datos).then(resultado => {
		if (resultado.status !== "success") return;
		const tipoMedicamento = resultado.resultado;
		$("#id_tipo_medicamento").val(tipoMedicamento.id_tipo_medicamento);
		campoNombre.val(tipoMedicamento.nom_tipo_medicamento);
		campoEstado.val(tipoMedicamento.estado);
		tituloModal.text("Actualizar Tipo de Medicamento");
	});
});

tablaTipoMedicamentos.on("click", ".btn-eliminar", function () {
	const datos = new FormData();
	datos.append("eliminar", "true");
	datos.append("id", $(this).val());
	alertEliminar("post", datos, () => obtenerDatos(filtrar.val(), paginaActual));
});

formularioTipoMedicamento.on("submit", event => {
	event.preventDefault();
	if (!formularioTipoMedicamento.valid()) return;
	const datos = new FormData(formularioTipoMedicamento[0]);
	const id = $("#id_tipo_medicamento").val();
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	peticionAjax(datos).then(resultado => {
		if (resultado.status !== "success") {
			if (resultado.errors) {
				if (resultado.errors.nombre) {
					campoNombre.addClass("is-invalid");
					errorNombre.text(resultado.errors.nombre);
				}
				if (resultado.errors.estado_tipo_medicamento) {
					campoEstado.addClass("is-invalid");
					errorEstado.text(resultado.errors.estado_tipo_medicamento);
				}
			}
			Swal.fire({ title: "Error", text: "No se pudo guardar el tipo de medicamento", icon: "error" });
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
