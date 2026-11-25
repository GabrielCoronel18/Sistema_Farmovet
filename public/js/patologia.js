const formularioPatologia = $(".PatologiaForm");
const tablaPatologias = $("#TablaPatologias");
const tituloModal = $("#TituloModalPatologia");
const btnAgregar = $("#btnAgregar");
const filtrar = $("#filtrar");
const campoNombre = $("#nombre");
const campoTipo = $("#tipo");
const campoSintomas = $("#sintomas");
const campoEstado = $("#estado_patologia");
const errorNombre = $("#error-nombre");
const errorTipo = $("#error-tipo");
const errorSintomas = $("#error-sintomas");
const errorEstado = $("#error-estado");

const tieneAcciones = $("#acciones").length > 0;

const columnas = [
	{ data: "id_patologia", className: "table-light" },
	{ data: "nombre", className: "table-light" },
	{ data: "tipo", className: "table-light" },
	{ data: "sintomas", className: "table-light" },
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
		data: "id_patologia",
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

const tabla = tablaPatologias.DataTable({
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
				console.error("No se pudieron cargar las patologias:", error);
				callback({
					draw: data.draw,
					recordsTotal: 0,
					recordsFiltered: 0,
					data: []
				});
			});
	}
});

filtrar.on("input", function () {
	tabla.search(this.value).draw();
});

btnAgregar.on("click", function () {
	formularioPatologia[0].reset();
	tituloModal.text("Agregar Nueva Patología");
	$("#id_patologia").val("");
});

tablaPatologias.on("click", ".btn-actualizar", function (e) {
	e.preventDefault();
	tituloModal.text("Actualizar Patología");
	const id = $(this).val();
	const datos = new FormData();
	datos.append("obtenerPatologia", "true");
	datos.append("id", id);

	peticionAjax(datos).then(resultado => {
		if (resultado.status === "success") {
			const result = resultado.resultado;
			$("#id_patologia").val(result.id_patologia);
			campoNombre.val(result.nombre);
			campoTipo.val(result.tipo);
			campoSintomas.val(result.sintomas);
			if (campoEstado.length) campoEstado.val(result.estado);
		} else {
			Swal.fire({ title: "Error", text: "Error al obtener el registro", icon: "error" });
		}
	});
});

formularioPatologia.on("submit", function (e) {
	e.preventDefault();
	if (!formularioPatologia.valid()) return;

	const datos = new FormData(formularioPatologia[0]);
	const id = $("#id_patologia").val();

	if (id === "") {
		datos.append("agregar", "true");
	} else {
		datos.append("actualizar", "true");
		datos.append("id", id);
	}

	peticionAjax(datos).then(resultado => {
		if (resultado.status === "success") {
			id === "" ? alertAgregar("success") : alertActualizar("success");
			const modalInstance = bootstrap.Modal.getInstance(document.getElementById("ModalAgregar"));
			if (modalInstance) modalInstance.hide();
			tabla.ajax.reload(null, false);
		} else if (resultado.status === "error") {
			if (resultado.errors) {
				if (resultado.errors.nombre) {
					campoNombre.addClass("is-invalid");
					errorNombre.text(resultado.errors.nombre);
				}
				if (resultado.errors.tipo) {
					campoTipo.addClass("is-invalid");
					errorTipo.text(resultado.errors.tipo);
				}
				if (resultado.errors.sintomas) {
					campoSintomas.addClass("is-invalid");
					errorSintomas.text(resultado.errors.sintomas);
				}
				if (resultado.errors.estado) {
					campoEstado.addClass("is-invalid");
					errorEstado.text(resultado.errors.estado);
				}
			}
			id === "" ? alertAgregar("error") : alertActualizar("error");
		}
	});
});

tablaPatologias.on("click", ".btn-eliminar", function (e) {
	e.preventDefault();
	const id = $(this).val();
	const datos = new FormData();
	datos.append("eliminar", "true");
	datos.append("id", id);
	alertEliminar("post", datos, () => tabla.ajax.reload(null, false));
});