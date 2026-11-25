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

const tieneAcciones = $("#acciones").length > 0;

const columnas = [
	{ data: "id_presentacion", className: "table-light" },
	{ data: "nombre_presentacion", className: "table-light" },
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
		data: "id_presentacion",
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

const tabla = tablaPresentaciones.DataTable({
	
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
				console.error("No se pudieron cargar las presntaciones:", error);
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
	alertEliminar("post", datos, () => tabla.ajax.reload(null,false));
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
		tabla.ajax.reload(null,false)
	}).catch(error => {
		console.error("Error al guardar la presentación:", error);
		Swal.fire({
			title: "Error",
			text: "No se pudo procesar la respuesta del servidor",
			icon: "error"
		});
	});
});

