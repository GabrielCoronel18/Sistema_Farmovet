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


const tieneAcciones = $("#acciones").length > 0;

const columnas = [
	{ data: "id_medicamento", className: "table-light" },
	{ data: "nombre_medicamento", className: "table-light" },
	{ data: "nom_tipo_medicamento", className: "table-light" },
	{ data: "nombre_presentacion", className: "table-light" },
	{
		data: "estado_medicamento",
		className: "table-light",
		render: function (estado) {
			const activo = Number(estado) === 1;
			return `<span class="badge ${activo ? "bg-success" : "bg-secondary"}">${activo ? "Activo" : "Inactivo"}</span>`;
		}
	}
];

if (tieneAcciones) {
	columnas.push({
		data: "id_medicamento",
		className: "table-light",
		orderable: false,
		searchable: false,
		render: function (id) {
			return `
				<button type="button" class="btn btn-sm btn-success btn-actualizar" value="${id}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
				<button type="button" class="btn btn-sm btn-danger btn-eliminar" value="${id}">Eliminar</button>
			`;
		}
	});
}

const tabla = tablaMedicamentos.DataTable({
	processing: true,
	serverSide: true,
	scrollX: true,
	searching: true,
	lengthChange: false,
	pageLength: 10,
	layout: {
		topEnd: null
	},
	columns: columnas,
	language: {
		processing: "Procesando...",
		search: "Buscar:",
		lengthMenu: "Mostrar _MENU_ registros",
		info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
		infoFiltered: "(filtrado de un total de _MAX_ registros)",
		loadingRecords: "Cargando...",
		zeroRecords: "No se encontraron resultados",
		emptyTable: "Ningún dato disponible en esta tabla",
		paginate: {
			first: "Primero",
			previous: "Anterior",
			next: "Siguiente",
			last: "Último"
		},
		aria: {
			sortAscending: ": Activar para ordenar la columna de manera ascendente",
			sortDescending: ": Activar para ordenar la columna de manera descendente"
		}
	},
	ajax: function (data, callback) {
		const datos = new FormData();
		datos.append("obtener", "true");
		datos.append("draw", data.draw);
		datos.append("start", data.start);
		datos.append("length", data.length);
		datos.append("search[value]", data.search.value);

		peticionAjax(datos)
			.then(resultado => {
				if (!Array.isArray(resultado.data)) {
					throw new Error("El servidor devolvió un formato de respuesta inválido.");
				}

				callback({
					draw: resultado.draw,
					recordsTotal: resultado.recordsTotal,
					recordsFiltered: resultado.recordsFiltered,
					data: resultado.data
				});
			})
			.catch(error => {
				console.error("No se pudieron cargar los medicamentos:", error);
				callback({
					draw: data.draw,
					recordsTotal: 0,
					recordsFiltered: 0,
					data: []
				});
			});
	}
});

filtrar.on("input", () => {
	tabla.search(filtrar.val()).draw();
});

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


cargarCatalogos().catch(() => {
	Swal.fire({ title: "Error", text: "No se pudieron cargar los catálogos", icon: "error" });
});



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
	alertEliminar("post", datos, () => tabla.ajax.reload(null, false));
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
		tabla.ajax.reload(null, false);
		id === "" ? alertAgregar("success") : alertActualizar("success");
		bootstrap.Modal.getInstance($("#ModalAgregar")[0]).hide();

	});
});