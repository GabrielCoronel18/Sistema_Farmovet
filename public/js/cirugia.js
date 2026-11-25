
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


const tieneAcciones = $("#acciones").length > 0;

const columnas = [
	{ data: "id_cirugia", className: "table-light" },
	{ data: "nombre_cirugia", className: "table-light" },
	{ data: "gravedad", className: "table-light" },
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
		data: "id_cirugia",
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

const tabla = tablaCirugias.DataTable({
	
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
				console.error("No se pudieron cargar las cirugias:", error);
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
	alertEliminar("post", datos, () => tabla.ajax.reload(null,false));
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
		tabla.ajax.reload(null,false)
	});
});

