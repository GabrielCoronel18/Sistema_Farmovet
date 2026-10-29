const formularioPresentacion = document.querySelector(".PresentacionForm");
const tablaPresentaciones = document.getElementById("TablaPresentaciones");
const tituloModal = document.getElementById("TituloModalPresentacion");
const btnAgregar = document.getElementById("btnAgregar");
const filtrar = document.getElementById("filtrar");
const limite = document.getElementById("limite");
const btnAnterior = document.getElementById("btn-anterior");
const btnSiguiente = document.getElementById("btn-siguiente");
const infoPagina = document.getElementById("info-pagina");
const errorNombre = document.getElementById("error-nombre");
const errorEstado = document.getElementById("error-estado_presentacion");
const campoNombre = document.getElementById("nombre");
const campoEstado = document.getElementById("estado_presentacion");

let paginaActual = 1;

function obtenerDatos(param = null, pagina = paginaActual) {
	const datos = new FormData();
	datos.append("obtener", "true");
	datos.append("pagina", pagina);
	datos.append("limite", limite.value);
	if (param !== null) datos.append("parametro", param);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			tablaPresentaciones.innerHTML = "";
			if (resultado.status !== "success") {
				tablaPresentaciones.innerHTML = "<tr><td colspan='3'>No hay presentaciones registradas</td></tr>";
				paginaActual = pagina;
				actualizarPaginacion(0);
				return;
			}
			if (resultado.resultados.length === 0 && pagina > 1) {
				obtenerDatos(param, pagina - 1);
				return;
			}

			resultado.resultados.forEach(presentacion => {
				tablaPresentaciones.innerHTML += `<tr>
					<td class="table-light">${presentacion.id_presentacion}</td>
					<td class="table-light">${presentacion.nombre_presentacion}</td>
					<td class="table-light">${presentacion.estado == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-secondary'>Inactivo</span>"}</td>
					<td class="table-light">
						<button class="btn btn-sm btn-success btn-actualizar" value="${presentacion.id_presentacion}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
						<button class="btn btn-sm btn-danger btn-eliminar" value="${presentacion.id_presentacion}">Eliminar</button>
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

obtenerDatos();

btnAgregar.addEventListener("click", () => {
	formularioPresentacion.reset();
	document.getElementById("id_presentacion").value = "";
	tituloModal.innerText = "Agregar Nueva Presentación";
});

tablaPresentaciones.addEventListener("click", event => {
	if (event.target.classList.contains("btn-actualizar")) {
		const datos = new FormData();
		datos.append("obtenerPresentacion", "true");
		datos.append("id", event.target.value);

		fetch(window.location, { method: "POST", body: datos })
			.then(respuesta => respuesta.json())
			.then(resultado => {
				if (resultado.status !== "success") return;
				const presentacion = resultado.resultado;
				document.getElementById("id_presentacion").value = presentacion.id_presentacion;
				document.getElementById("nombre").value = presentacion.nombre_presentacion;
				const estadoInput = document.getElementById("estado_presentacion");
				if (estadoInput) estadoInput.value = presentacion.estado;
				tituloModal.innerText = "Actualizar Presentación";
			});
	}

	if (event.target.classList.contains("btn-eliminar")) {
		const datos = new FormData();
		datos.append("eliminar", "true");
		datos.append("id", event.target.value);
		alertEliminar("post", datos, () => obtenerDatos(filtrar.value, paginaActual));
	}
});

 formularioPresentacion.addEventListener("submit", event => {
	event.preventDefault();
	campoNombre.classList.remove("is-invalid");
	errorNombre.textContent = "";
	if (campoEstado) {
		campoEstado.classList.remove("is-invalid");
		errorEstado.textContent = "";
	}

	const datos = new FormData(formularioPresentacion);
	const id = document.getElementById("id_presentacion").value;
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			if (resultado.status !== "success") {
				if (resultado.errors) {
					if (resultado.errors.nombre) {
						errorNombre.textContent = resultado.errors.nombre;
						campoNombre.classList.add("is-invalid");
					} else {
						errorNombre.textContent = "";
						campoNombre.classList.remove("is-invalid");
					}
					if (campoEstado && resultado.errors.estado_presentacion) {
						errorEstado.textContent = resultado.errors.estado_presentacion;
						campoEstado.classList.add("is-invalid");
					} else {
						errorEstado.textContent = "";
						if (campoEstado) campoEstado.classList.remove("is-invalid");
					}
				}

				Swal.fire({ title: "Error", text: "No se pudo guardar la presentación", icon: "error" });
				return;
			}
			id === "" ? alertAgregar("success") : alertActualizar("success");
			bootstrap.Modal.getInstance(document.getElementById("ModalAgregar")).hide();
			obtenerDatos();
		})
		.catch(error => {
			console.error("Error al guardar la presentación:", error);
			Swal.fire({
				title: "Error",
				text: "No se pudo procesar la respuesta del servidor",
				icon: "error"
			});
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
