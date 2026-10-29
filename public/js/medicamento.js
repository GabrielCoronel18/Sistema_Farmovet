const formularioMedicamento = document.querySelector(".MedicamentoForm");
const tablaMedicamentos = document.getElementById("TablaMedicamentos");
const tituloModal = document.getElementById("TituloModalMedicamento");
const btnAgregar = document.getElementById("btnAgregar");
const filtrar = document.getElementById("filtrar");
const selectTipoMedicamento = document.getElementById("tipo");
const selectPresentacion = document.getElementById("presentacion");
const limite = document.getElementById("limite");
const btnAnterior = document.getElementById("btn-anterior");
const btnSiguiente = document.getElementById("btn-siguiente");
const infoPagina = document.getElementById("info-pagina");
const campoNombre = document.getElementById("nombre");
const campoEstado = document.getElementById("estado_medicamento");
const errorNombre = document.getElementById("error-nombre");
const errorTipo = document.getElementById("error-tipo");
const errorPresentacion = document.getElementById("error-presentacion");
const errorEstado = document.getElementById("error-estado");
let paginaActual = 1;

function solicitar(datos) {
	return fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json());
}

function cargarSelect(select, opciones, id, nombre, textoInicial) {
	select.innerHTML = `<option value="">${textoInicial}</option>`;
	opciones.forEach(opcion => {
		select.insertAdjacentHTML("beforeend", `<option value="${opcion[id]}">${opcion[nombre]}</option>`);
	});
}

function cargarCatalogos() {
	const tipos = new FormData();
	tipos.append("obtenerTipos", "true");
	const presentaciones = new FormData();
	presentaciones.append("obtenerPresentaciones", "true");

	return Promise.all([solicitar(tipos), solicitar(presentaciones)])
		.then(([resultadoTipos, resultadoPresentaciones]) => {
			if (resultadoTipos.status !== "success" || resultadoPresentaciones.status !== "success") {
				throw new Error("No se pudieron cargar los catálogos");
			}
			cargarSelect(selectTipoMedicamento, resultadoTipos.resultado, "id_tipo_medicamento", "nom_tipo_medicamento", "Seleccione un tipo");
			cargarSelect(selectPresentacion, resultadoPresentaciones.resultado, "id_presentacion", "nombre_presentacion", "Seleccione una presentación");
		});
}

function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", "true");
	datos.append("pagina", pagina);
	datos.append("limite", limite.value);
	if (param !== null) datos.append("parametro", param);

	solicitar(datos).then(resultado => {
		tablaMedicamentos.innerHTML = "";
		if (resultado.status !== "success") {
			tablaMedicamentos.innerHTML = "<tr><td colspan='5'>No hay medicamentos registrados</td></tr>";
			paginaActual = pagina;
				actualizarPaginacion(0);
			return;
		}
		if (resultado.resultados.length === 0 && pagina > 1) {
			obtenerDatos(param, pagina - 1);
			return;
		}

		resultado.resultados.forEach(medicamento => {
			tablaMedicamentos.innerHTML += `<tr>
				<td class="table-light">${medicamento.id_medicamento}</td>
				<td class="table-light">${medicamento.nombre_medicamento}</td>
				<td class="table-light">${medicamento.nom_tipo_medicamento}</td>
				<td class="table-light">${medicamento.nombre_presentacion}</td>
				<td class="table-light">${medicamento.estado == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-secondary'>Inactivo</span>"}</td>
				<td class="table-light">
					<button class="btn btn-sm btn-success btn-actualizar" value="${medicamento.id_medicamento}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
					<button class="btn btn-sm btn-danger btn-eliminar" value="${medicamento.id_medicamento}">Eliminar</button>
				</td>
			</tr>`;
		});
		paginaActual = pagina;
		actualizarPaginacion(resultado.hasMore);
	});
}
function actualizarPaginacion(hasMore) {
	btnAnterior.parentElement.classList.toggle("disabled", paginaActual === 1);
	btnAnterior.disabled = paginaActual === 1;
	btnSiguiente.disabled = !hasMore;
	infoPagina.innerText = `Página ${paginaActual}`;
}


cargarCatalogos().catch(() => {
	Swal.fire({ title: "Error", text: "No se pudieron cargar los catálogos", icon: "error" });
});


obtenerDatos();


btnAgregar.addEventListener("click", () => {
	formularioMedicamento.reset();
	document.getElementById("id_medicamento").value = "";
	tituloModal.innerText = "Agregar Nuevo Medicamento";
});

tablaMedicamentos.addEventListener("click", event => {
	if (event.target.classList.contains("btn-actualizar")) {
		const datos = new FormData();
		datos.append("obtenerMedicamento", "true");
		datos.append("id", event.target.value);

		solicitar(datos).then(resultado => {
			if (resultado.status !== "success") return;
			const medicamento = resultado.resultado;
			document.getElementById("id_medicamento").value = medicamento.id_medicamento;
			document.getElementById("nombre").value = medicamento.nombre_medicamento;
			selectTipoMedicamento.value = medicamento.id_tipo_medicamento;
			selectPresentacion.value = medicamento.id_presentacion;
			const estadoInput = document.getElementById("estado_medicamento");
			if (estadoInput) estadoInput.value = medicamento.estado;
			tituloModal.innerText = "Actualizar Medicamento";
		});
	}

	if (event.target.classList.contains("btn-eliminar")) {
		const datos = new FormData();
		datos.append("eliminar", "true");
		datos.append("id", event.target.value);
		alertEliminar("post", datos, () => obtenerDatos(filtrar.value, paginaActual));
	}
});

formularioMedicamento.addEventListener("submit", event => {
	event.preventDefault();
	const datos = new FormData(formularioMedicamento);
	const id = document.getElementById("id_medicamento").value;
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	solicitar(datos).then(resultado => {
		if (resultado.status !== "success") {
			if(resultado.errors) {
				if(resultado.errors.nombre) {
					campoNombre.classList.add("is-invalid");
					errorNombre.innerText = resultado.errors.nombre;
				}
				if(resultado.errors.tipo) {
					selectTipoMedicamento.classList.add("is-invalid");
					errorTipo.innerText = resultado.errors.tipo;
				}
				if(resultado.errors.presentacion) {
					selectPresentacion.classList.add("is-invalid");
					errorPresentacion.innerText = resultado.errors.presentacion;
				}
				if(resultado.errors.estado) {
					campoEstado.classList.add("is-invalid");
					errorEstado.innerText = resultado.errors.estado;
				}
			}
			Swal.fire({ title: "Error", text: "No se pudo guardar el medicamento", icon: "error" });
			return;
		}
		id === "" ? alertAgregar("success") : alertActualizar("success");
		bootstrap.Modal.getInstance(document.getElementById("ModalAgregar")).hide();
		obtenerDatos();
	});
});



filtrar.addEventListener("input", () => {
	paginaActual = 1;
	obtenerDatos(filtrar.value, paginaActual);
});

limite.addEventListener("change", () => {
	paginaActual = 1;
	obtenerDatos(filtrar.value, paginaActual);
});

btnAnterior.addEventListener("click", () => {
	if (paginaActual > 1) obtenerDatos(filtrar.value, paginaActual - 1);
});

btnSiguiente.addEventListener("click", () => {
	if (!btnSiguiente.disabled) obtenerDatos(filtrar.value, paginaActual + 1);
});
