const formAlergia = $('#formAlergia');
const btnAgregar = $('#btnAgregar');
const errorNombre = $('#errorNombre');
const errorTipo = $('#errorTipo');
const campoNombre = $('#nombre');
const campoTipo = $('#tipo');
const inputFiltrar = $('#filtrar');
const tablaAlergia = $('#TablaAlergias');
const campoEstado = $('#estado');

const tieneAcciones = $("#acciones").length > 0;

const columnas = [
	{ data: "id_alergia", className: "table-light" },
	{ data: "nombre_alergia", className: "table-light" },
	{ data: "tipo", className: "table-light" },
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
		data: "id_alergia",
		className: "table-light",
		orderable: false,
		searchable: false,
		render: function (id) {
	    return `
		<button type="button" class="btn btn-sm btn-success btn-actualizar" value="${id}">Actualizar</button>
		<button type="button" class="btn btn-sm btn-danger btn-eliminar" value="${id}">Eliminar</button>
	`;
}
	});
}

const tabla = tablaAlergia.DataTable({
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
		datos.append("consultar", "true");
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
				console.error("No se pudieron cargar las alergias:", error);
				callback({
					draw: data.draw,
					recordsTotal: 0,
					recordsFiltered: 0,
					data: []
				});
			});
	}
});

inputFiltrar.on("input", function () {
	tabla.search($(this).val()).draw();
});

function limpiarErroresFormulario() {
	formAlergia.validate().resetForm();
	campoNombre.removeClass('is-invalid');
	campoTipo.removeClass('is-invalid');
	errorNombre.text('');
	errorTipo.text('');
}

if (btnAgregar.length) {
	btnAgregar.on('click', function () {
		if (formAlergia.length) formAlergia[0].reset();
		limpiarErroresFormulario();
		$('#id_alergia').val('');
        campoEstado.val('1');
		$('#TituloModalAlergia').text('Agregar Alergia');
	});
}

tablaAlergia.on('click', '.btn-actualizar', function () {
	const id = $(this).val();
	actualizarAlergia(id);
});

tablaAlergia.on('click', '.btn-eliminar', function () {
	const id = $(this).val();
	eliminarAlergia(id);
});


if (formAlergia.length) {
	formAlergia.on('submit', function (e) {
		e.preventDefault();
		if (!formAlergia.valid()) return;

		const formData = new FormData(formAlergia[0]);
		const id = $('#id_alergia').val();
		formData.append(id === '' ? 'agregar' : 'actualizar', true);

		peticionAjax(formData)
			.then(data => {
				if (data.status === 'success') {
					const modalElement = $('#ModalAgregar')[0];
					const modal = bootstrap.Modal.getInstance(modalElement);
					if (modal) modal.hide();

					id === "" ? alertAgregar("success", data.message) : alertActualizar("success", data.message);

					formAlergia[0].reset();
					limpiarErroresFormulario();
					tabla.ajax.reload(null, false);
				} else if (data.status === 'error') {
					const errors = data.errors || {};
					if (errors.nombre) {
						campoNombre.addClass("is-invalid");
						errorNombre.text(errors.nombre);
					}
					if (errors.tipo) {
						campoTipo.addClass("is-invalid");
						errorTipo.text(errors.tipo);
					}
					id === "" ? alertAgregar("error", data.message) : alertActualizar("error", data.message);
				}
			})
			.catch(error => {
				console.error('Error al guardar la alergia:', error);
			});
	});
}

function actualizarAlergia(id) {
	const formData = new FormData();
	formData.append('consultarPorId', true);
	formData.append('id', id);

	formAlergia.validate().resetForm();
	formAlergia.find('.is-invalid').removeClass('is-invalid');
	errorNombre.text('');
	errorTipo.text('');

	return peticionAjax(formData).then(data => {
		if (data.status !== 'success' || !data.resultado) {
			Swal.fire('Error', 'No se pudo cargar la alergia seleccionada.', 'error');
			return;
		}

		$('#id_alergia').val(id);
        campoNombre.val(data.resultado.nombre_alergia);
        campoTipo.val(data.resultado.tipo);
        campoEstado.val(String(data.resultado.estado));
        $('#TituloModalAlergia').text('Editar Alergia');

		bootstrap.Modal.getOrCreateInstance($('#ModalAgregar')[0]).show();
	}).catch(error => {
		console.error('Error al cargar la alergia:', error);
	});
}

function eliminarAlergia(id) {
	const datos = new FormData();
	datos.append('eliminar', true);
	datos.append('id', id);

	alertEliminar('POST', datos, function () {
		tabla.ajax.reload(null, false);
	});
}