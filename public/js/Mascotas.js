const formularioMascotas = $(".MascotasForm");
const tablaMascotas = $("#TablaMascotas");
const tituloModal = $("#TituloModalMascotas");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const limite = $("#limite");
const btnAnterior = $("#btn-anterior");
const btnSiguiente = $("#btn-siguiente");
const infoPagina = $("#info-pagina");
const selectRaza = $("#id_raza");
const selectCliente = $("#cedula_cliente");
const camposValidacion = [
	{ error: "nombre", campo: "#nombre", mensaje: "#error-nombre" },
	{ error: "edad", campo: "#edad", mensaje: "#error-edad" },
	{ error: "fch_nacimiento", campo: "#fch_nacimiento", mensaje: "#error-fch_nacimiento" },
	{ error: "sexo", campo: "#sexo", mensaje: "#error-sexo" },
	{ error: "chip", campo: "#chip", mensaje: "#error-chip" },
	{ error: "raza", campo: "#id_raza", mensaje: "#error-id_raza" },
	{ error: "pelaje", campo: "#pelaje", mensaje: "#error-pelaje" },
	{ error: "cedula", campo: "#cedula_cliente", mensaje: "#error-cedula_cliente" },
	{ error: "procedencia", campo: "#procedencia", mensaje: "#error-procedencia" }
];
let paginaActual = 1;
let selectAlergias;
let selectEnfermedades;
let selectCirugias;

function limpiarErroresValidacion() {
	camposValidacion.forEach(({ campo, mensaje }) => {
		$(campo).removeClass("is-invalid");
		$(mensaje).text("");
	});
}

function renderizarErroresValidacion(errores = {}) {
	camposValidacion.forEach(({ error, campo, mensaje }) => {
		if (errores[error]) {
			$(campo).addClass("is-invalid");
			$(mensaje).text(errores[error]);
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

	return peticionAjax(datos, null, () => {}).then(resultado => {
		if (resultado.status !== "success") {
			throw new Error("No se pudieron obtener los catálogos.");
		}

		resultado.razas.forEach(raza => {
			selectRaza.append($("<option>").val(raza.id_raza).text(raza.nombre_raza));
		});
		resultado.clientes.forEach(cliente => {
			const nombre = `${cliente.nombre} ${cliente.apellido} (${cliente.cedula_cliente})`;
			selectCliente.append($("<option>").val(cliente.cedula_cliente).text(nombre));
		});

		inicializarSelectsAntecedentes();
	}).catch(error => {
		console.error(error);
		Swal.fire({
			title: "Error",
			text: "No se pudieron cargar los catálogos de razas y clientes.",
			icon: "error"
		});
		throw error;
	});
}

function actualizarPaginacion(hayMas) {
	btnAnterior.parent().toggleClass("disabled", paginaActual === 1);
	btnAnterior.prop("disabled", paginaActual === 1);
	btnSiguiente.prop("disabled", !hayMas);
	infoPagina.text(`Página ${paginaActual}`);
}

function crearCelda(valor, clases = "table-light") {
	return $("<td>").addClass(clases).text(valor ?? "");
}

function crearCeldaAntecedentes(valor) {
	const badge = $("<span>")
		.addClass(valor ? "badge bg-success" : "badge bg-secondary")
		.text(valor || "Ninguna");
	return $("<td>").addClass("table-light").append(badge);
}

function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", true);
	datos.append("pagina", pagina);
	datos.append("limite", limite.val());
	if (param !== null) datos.append("parametro", param);

	peticionAjax(datos).then(resultado => {
		tablaMascotas.empty();
		if (resultado.status !== "success") {
			tablaMascotas.append(
				$("<tr>").append(
					$("<td>").attr("colspan", 14)
						.addClass("text-center text-muted py-4")
						.text("No hay mascotas registradas")
				)
			);
			paginaActual = pagina;
			actualizarPaginacion(false);
			return;
		}
		if (resultado.resultados.length === 0 && pagina > 1) {
			obtenerDatos(param, pagina - 1);
			return;
		}

		resultado.resultados.forEach(mascota => {
			const fila = $("<tr>");
			fila.append(crearCelda(mascota.id_mascota_hash));
			fila.append(crearCelda(mascota.nombre));
			fila.append(crearCelda(mascota.edad));
			fila.append(crearCelda(mascota.sexo));
			fila.append(crearCelda(mascota.chip));
			fila.append(crearCelda(mascota.procedencia, "table-light td-large"));
			fila.append(crearCelda(mascota.fch_nacimiento));
			fila.append(crearCelda(mascota.nombre_raza));
			fila.append(crearCelda(mascota.pelaje));
			fila.append(crearCelda(`${mascota.cedula_cliente} - ${mascota.nombre_cliente}`));
			fila.append(crearCeldaAntecedentes(mascota.alergias));
			fila.append(crearCeldaAntecedentes(mascota.enfermedades));
			fila.append(crearCeldaAntecedentes(mascota.cirugias));

			const actualizar = $("<button>").addClass("btn btn-sm btn-success btn-actualizar")
				.val(mascota.id_mascota_hash).attr("data-bs-toggle", "modal")
				.attr("data-bs-target", "#ModalAgregar").text("Actualizar");
			const eliminar = $("<button>").addClass("btn btn-sm btn-danger btn-eliminar")
				.val(mascota.id_mascota_hash).text("Eliminar");
			fila.append(
				$("<td>").addClass("table-light text-nowrap mascotas-acciones")
					.append(actualizar, " ", eliminar)
			);
			tablaMascotas.append(fila);
		});
		paginaActual = pagina;
		actualizarPaginacion(resultado.hayMas);
	});
}

function cargarAntecedentesEnFormulario(id) {
	const datos = new FormData();
	datos.append("obtenerAntecedentes", true);
	datos.append("id", id);

	peticionAjax(datos).then(resultado => {
		if (resultado.status !== "success") return;
		selectAlergias.setValue(resultado.alergias.map(alergia => String(alergia.id_alergia)));
		selectEnfermedades.setValue(resultado.enfermedades.map(enfermedad => String(enfermedad.id_patologia)));
		selectCirugias.setValue(resultado.cirugias.map(cirugia => String(cirugia.id_cirugia)));
	});
}

cargarCatalogos().then(() => obtenerDatos()).catch(() => {});

btnAgregar.on("click", () => {
	formularioMascotas[0].reset();
	limpiarErroresValidacion();
	selectAlergias.clear(true);
	selectEnfermedades.clear(true);
	selectCirugias.clear(true);
	tituloModal.text("Agregar Nueva Mascota");
	$("#id_mascota").val("");
});

tablaMascotas.on("click", ".btn-actualizar", function (event) {
	event.preventDefault();
	tituloModal.text("Actualizar Mascota");
	const datos = new FormData();
	datos.append("obtenerMascota", true);
	datos.append("id", $(this).val());

	peticionAjax(datos).then(resultado => {
		if (resultado.status !== "success") {
			Swal.fire({ title: "Error", text: "Error al obtener el registro", icon: "error" });
			return;
		}
		const mascota = resultado.resultado;
		$("#id_mascota").val(mascota.id_mascota_hash);
		$("#nombre").val(mascota.nombre);
		$("#edad").val(mascota.edad);
		$("#sexo").val(mascota.sexo);
		$("#fch_nacimiento").val(mascota.fch_nacimiento);
		$("#chip").val(mascota.chip);
		selectRaza.val(mascota.id_raza);
		$("#pelaje").val(mascota.pelaje);
		selectCliente.val(mascota.cedula_cliente);
		$("#procedencia").val(mascota.procedencia);
		cargarAntecedentesEnFormulario(mascota.id_mascota_hash);
	});
});

formularioMascotas.on("submit", event => {
	event.preventDefault();
	if (!formularioMascotas.valid()) return;
	limpiarErroresValidacion();
	const datos = new FormData(formularioMascotas[0]);
	const id = $("#id_mascota").val();
	datos.append(id === "" ? "agregar" : "actualizar", true);
	if (id !== "") datos.append("id", id);

	peticionAjax(datos).then(resultado => {
		if (resultado.status === "success") {
			id === "" ? alertAgregar("success") : alertActualizar("success");
			bootstrap.Modal.getInstance($("#ModalAgregar")[0]).hide();
			obtenerDatos(filtrar.val(), paginaActual);
			return;
		}
		renderizarErroresValidacion(resultado.errors);
		id === "" ? alertAgregar("error") : alertActualizar("error");
	});
});

tablaMascotas.on("click", ".btn-eliminar", function (event) {
	event.preventDefault();
	const datos = new FormData();
	datos.append("eliminar", true);
	datos.append("id", $(this).val());
	alertEliminar("post", datos, () => obtenerDatos(filtrar.val(), paginaActual));
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
