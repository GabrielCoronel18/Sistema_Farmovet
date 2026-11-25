const formularioMascotas = $(".MascotasForm");
const tablaMascotas = $("#mascotas-tabla");
const tituloModal = $("#TituloModalMascotas");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const selectRaza = $("#id_raza");
const selectCliente = $("#cedula_cliente");
const tieneAcciones = $("#acciones").length > 0;
const renderTexto = $.fn.dataTable.render.text();
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

function escaparHtml(valor) {
	return String(valor ?? "").replace(/[&<>"']/g, caracter => ({
		"&": "&amp;",
		"<": "&lt;",
		">": "&gt;",
		'"': "&quot;",
		"'": "&#39;"
	})[caracter]);
}

function renderizarAntecedente(valor, tipo) {
	if (tipo !== "display") return valor || "Ninguna";
	return `<span class="badge ${valor ? "bg-success" : "bg-secondary"}">${escaparHtml(valor || "Ninguna")}</span>`;
}

const columnasMascotas = [
	{ data: "id_mascota_hash", className: "table-light td-nowrap td-left", render: renderTexto },
	{ data: "nombre", className: "table-light", render: renderTexto },
	{ data: "edad", className: "table-light", render: renderTexto },
	{ data: "sexo", className: "table-light", render: renderTexto },
	{ data: "chip", className: "table-light", render: renderTexto },
	{ data: "procedencia", className: "table-light td-large", render: renderTexto },
	{ data: "fch_nacimiento", className: "table-light", render: renderTexto },
	{ data: "nombre_raza", className: "table-light", render: renderTexto },
	{ data: "pelaje", className: "table-light", render: renderTexto },
	{
		data: null,
		className: "table-light td-nowrap td-left",
		render: (_, tipo, mascota) => {
			const cliente = `${mascota.cedula_cliente ?? ""} - ${mascota.nombre_cliente ?? ""}`;
			return tipo === "display" ? escaparHtml(cliente) : cliente;
		}
	},
	{ data: "alergias", className: "table-light", render: renderizarAntecedente },
	{ data: "enfermedades", className: "table-light", render: renderizarAntecedente },
	{ data: "cirugias", className: "table-light", render: renderizarAntecedente }
];

if (tieneAcciones) {
	columnasMascotas.push({
		data: "id_mascota_hash",
		className: "table-light text-nowrap mascotas-acciones",
		orderable: false,
		searchable: false,
		render: id => `<button type="button" class="btn btn-sm btn-success btn-actualizar" value="${escaparHtml(id)}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
			<button type="button" class="btn btn-sm btn-danger btn-eliminar" value="${escaparHtml(id)}">Eliminar</button>`
	});
}

const tabla = tablaMascotas.DataTable({
	serverSide: true,
	autoWidth: false,
	scrollX: true,
	searching: true,
	lengthChange: true,
	lengthMenu: [5, 10, 20],
	pageLength: 5,
	layout: { topStart: null, topEnd: null, bottomStart: "pageLength", bottomEnd: "paging" },
	columns: columnasMascotas,
	language: {
		lengthMenu: "Mostrar _MENU_ registros",
		info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
		infoFiltered: "(filtrado de un total de _MAX_ registros)",
		loadingRecords: "Cargando...",
		zeroRecords: "No se encontraron resultados",
		emptyTable: "Ninguna mascota registrada",
		paginate: { first: "Primero", previous: "Anterior", next: "Siguiente", last: "Último" }
	},
	ajax: function (request, callback) {
		const datos = new FormData();
		datos.append("obtener", true);
		datos.append("draw", request.draw);
		datos.append("start", request.start);
		datos.append("length", request.length);
		datos.append("search[value]", request.search.value);

		peticionAjax(datos)
			.then(resultado => {
				if (!Array.isArray(resultado.data)) {
					throw new Error("El servidor devolvió un formato de respuesta inválido.");
				}
				callback(resultado);
			})
			.catch(error => {
				console.error("No se pudieron cargar las mascotas:", error);
				Swal.fire({ title: "Error", text: "No se pudieron cargar las mascotas.", icon: "error" });
				callback({ draw: request.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
			});
	}
});

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

cargarCatalogos().catch(() => {});

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
			tabla.ajax.reload(null, id === "");
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
	alertEliminar("post", datos, () => tabla.ajax.reload());
});

filtrar.on("input", () => {
	tabla.search(filtrar.val()).draw();
});
