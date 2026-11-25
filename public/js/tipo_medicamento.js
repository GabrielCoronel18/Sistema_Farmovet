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

const tieneAcciones = $("#acciones").length > 0;

const columnas = [
	{ data: "id_tipo_medicamento", className: "table-light " },
	{ data: "nom_tipo_medicamento", className: "table-light" },
	{
		data: "estado",
		className: "table-light",
		render: function (estado) {
			const activo = Number(estado) === 1;
			return `<span class="badge ${activo ? "bg-success" : "bg-secondary"}">${activo ? "Activo" : "Inactivo"}</span>`;
		}
	}
];

if (tieneAcciones) {
	columnas.push({
		data: "id_tipo_medicamento",
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

const tabla = tablaTipoMedicamentos.DataTable({

	serverSide: true,
	autoWidth: false,
	scrollX: true,
	searching: true,
	lengthChange: true,
	lengthMenu: [5, 10, 20],
	pageLength: 5,
	layout: {
		topStart: null,    
		topEnd: null,
		bottomStart: "pageLength", 
		bottomEnd: "paging"
	},
	columns: columnas,
	language: {
		
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
				console.error("No se pudieron cargar los tipos de medicamentos:", error);
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
	alertEliminar("post", datos, () => tabla.ajax.reload(null, false));
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
		tabla.ajax.reload(null, false);
	});
});

