
const formularioCirugia = $(".CirugiaForm");
const tablaCirugias = $("#TablaCirugias");
const tituloModal = $("#TituloModalCirugia");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const campoNombre = $("#nombre_cirugia");
const errorNombre = $("#error-nombre");
const campoGravedad = $("#gravedad");
const errorGravedad = $("#error-gravedad");
const campoEstado = $("#estado");
const errorEstado = $("#error-estado");
const limite = $("#limite");
const btnAnterior = $("#btn-anterior");
const btnSiguiente = $("#btn-siguiente");
const infoPagina = $("#info-pagina");
let paginaActual = 1;


function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", "true");
	datos.append("pagina", pagina);
	datos.append("limite", limite.val());
	if (param !== null) datos.append("parametro", param);

	peticionAjax(datos , function(resultado) {
		tablaCirugias.empty();
		if (resultado.status !== "success") {
			tablaCirugias.append($("<tr>").append($("<td>").attr("colspan", 5).text("No hay cirugías registradas")));
			paginaActual = pagina;
			actualizarPaginacion(false);
			return;
		}
		if (resultado.resultados.length === 0 && pagina > 1) {
			obtenerDatos(param, pagina - 1);
			return;
		}

		resultado.resultados.forEach(cirugia => {
			const fila = $("<tr>");
			fila.append($("<td>").addClass("table-light").text(cirugia.id_cirugia));
			fila.append($("<td>").addClass("table-light").text(cirugia.nombre_cirugia));
			fila.append($("<td>").addClass("table-light").text(cirugia.gravedad));
			fila.append(
				$("<td>").addClass("table-light").append(
					$("<span>")
						.addClass(cirugia.estado == 1 ? "badge bg-success" : "badge bg-secondary")
						.text(cirugia.estado == 1 ? "Activo" : "Inactivo")
				)
			);
			const actualizar = $("<button>").addClass("btn btn-sm btn-success btn-actualizar")
				.val(cirugia.id_cirugia).attr("data-bs-toggle", "modal")
				.attr("data-bs-target", "#ModalAgregar").text("Actualizar");
			const eliminar = $("<button>").addClass("btn btn-sm btn-danger btn-eliminar")
				.val(cirugia.id_cirugia).text("Eliminar");

			if($('#acciones')){	
			fila.append($("<td>").addClass("table-light").append(actualizar, " ", eliminar));
			}
			
			tablaCirugias.append(fila);
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
	formularioCirugia[0].reset();
	$("#id_cirugia").val("");
	tituloModal.text("Agregar Nueva Cirugía");
});

tablaCirugias.on("click", ".btn-actualizar", function () {
	const datos = new FormData();
	datos.append("obtenerCirugia", "true");
	datos.append("id", $(this).val());

	peticionAjax(datos , function(resultado) {
		if (resultado.status !== "success") return;
		const cirugia = resultado.resultado;
		$("#id_cirugia").val(cirugia.id_cirugia);
		campoNombre.val(cirugia.nombre_cirugia);
		campoGravedad.val(cirugia.gravedad);
		campoEstado.val(cirugia.estado);
		tituloModal.text("Actualizar Cirugía");
	});
});

tablaCirugias.on("click", ".btn-eliminar", function () {
	const datos = new FormData();
	datos.append("eliminar", "true");
	datos.append("id", $(this).val());
	alertEliminar("post", datos, () => obtenerDatos(filtrar.val(), paginaActual));
});

formularioCirugia.on("submit", event => {
	event.preventDefault();
	if (!formularioCirugia.valid()) return;
	const datos = new FormData(formularioCirugia[0]);
	const id = $("#id_cirugia").val();
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	peticionAjax(datos , function(resultado) {
		if (resultado.status !== "success") {
			if (resultado.errors) {
				campoNombre.toggleClass("is-invalid", Boolean(resultado.errors.nombre));
				errorNombre.text(resultado.errors.nombre || "");
				campoGravedad.toggleClass("is-invalid", Boolean(resultado.errors.gravedad));
				errorGravedad.text(resultado.errors.gravedad || "");
				campoEstado.toggleClass("is-invalid", Boolean(resultado.errors.estado));
				errorEstado.text(resultado.errors.estado || "");
			}
			Swal.fire({ title: "Error", text: "No se pudo guardar la cirugía", icon: "error" });
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
