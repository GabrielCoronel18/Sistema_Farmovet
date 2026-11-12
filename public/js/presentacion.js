const formularioPresentacion = $(".PresentacionForm");
const tablaPresentaciones = $("#TablaPresentaciones");
const tituloModal = $("#TituloModalPresentacion");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const limite = $("#limite");
const btnAnterior = $("#btn-anterior");
const btnSiguiente = $("#btn-siguiente");
const infoPagina = $("#info-pagina");
const errorNombre = $("#error-nombre");
const errorEstado = $("#error-estado_presentacion");
const campoNombre = $("#nombre");
const campoEstado = $("#estado_presentacion");
let paginaActual = 1;

function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", "true");
	datos.append("pagina", pagina);
	datos.append("limite", limite.val());
	if (param !== null) datos.append("parametro", param);

	peticionAjax(datos).then(resultado => {
		tablaPresentaciones.empty();
		if (resultado.status !== "success") {
			tablaPresentaciones.append(
				$("<tr>").append($("<td>").attr("colspan", 4).text("No hay presentaciones registradas"))
			);
			paginaActual = pagina;
			actualizarPaginacion(false);
			return;
		}
		if (resultado.resultados.length === 0 && pagina > 1) {
			obtenerDatos(param, pagina - 1);
			return;
		}

		resultado.resultados.forEach(presentacion => {
			const fila = $("<tr>");
			fila.append($("<td>").addClass("table-light").text(presentacion.id_presentacion));
			fila.append($("<td>").addClass("table-light").text(presentacion.nombre_presentacion));
			fila.append(
				$("<td>").addClass("table-light").append(
					$("<span>")
						.addClass(presentacion.estado == 1 ? "badge bg-success" : "badge bg-secondary")
						.text(presentacion.estado == 1 ? "Activo" : "Inactivo")
				)
			);
			const actualizar = $("<button>").addClass("btn btn-sm btn-success btn-actualizar")
				.val(presentacion.id_presentacion).attr("data-bs-toggle", "modal")
				.attr("data-bs-target", "#ModalAgregar").text("Actualizar");
			const eliminar = $("<button>").addClass("btn btn-sm btn-danger btn-eliminar")
				.val(presentacion.id_presentacion).text("Eliminar");

			if($('#acciones')){
			fila.append($("<td>").addClass("table-light").append(actualizar, " ", eliminar));
			}
			
			tablaPresentaciones.append(fila);
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
	formularioPresentacion[0].reset();
	$("#id_presentacion").val("");
	tituloModal.text("Agregar Nueva Presentación");
});

tablaPresentaciones.on("click", ".btn-actualizar", function () {
	const datos = new FormData();
	datos.append("obtenerPresentacion", "true");
	datos.append("id", $(this).val());

	peticionAjax(datos, null, () => {}).then(resultado => {
		if (resultado.status !== "success") return;
		const presentacion = resultado.resultado;
		$("#id_presentacion").val(presentacion.id_presentacion);
		campoNombre.val(presentacion.nombre_presentacion);
		campoEstado.val(presentacion.estado);
		tituloModal.text("Actualizar Presentación");
	});
});

tablaPresentaciones.on("click", ".btn-eliminar", function () {
	const datos = new FormData();
	datos.append("eliminar", "true");
	datos.append("id", $(this).val());
	alertEliminar("post", datos, () => obtenerDatos(filtrar.val(), paginaActual));
});

formularioPresentacion.on("submit", event => {
	event.preventDefault();
	if (!formularioPresentacion.valid()) return;
	campoNombre.removeClass("is-invalid");
	errorNombre.text("");
	campoEstado.removeClass("is-invalid");
	errorEstado.text("");

	const datos = new FormData(formularioPresentacion[0]);
	const id = $("#id_presentacion").val();
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	peticionAjax(datos, null, () => {}).then(resultado => {
		if (resultado.status !== "success") {
			if (resultado.errors) {
				if (resultado.errors.nombre) {
					errorNombre.text(resultado.errors.nombre);
					campoNombre.addClass("is-invalid");
				}
				if (campoEstado.length && resultado.errors.estado_presentacion) {
					errorEstado.text(resultado.errors.estado_presentacion);
					campoEstado.addClass("is-invalid");
				}
			}
			Swal.fire({ title: "Error", text: "No se pudo guardar la presentación", icon: "error" });
			return;
		}
		id === "" ? alertAgregar("success") : alertActualizar("success");
		bootstrap.Modal.getInstance($("#ModalAgregar")[0]).hide();
		obtenerDatos(filtrar.val(), paginaActual);
	}).catch(error => {
		console.error("Error al guardar la presentación:", error);
		Swal.fire({
			title: "Error",
			text: "No se pudo procesar la respuesta del servidor",
			icon: "error"
		});
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
