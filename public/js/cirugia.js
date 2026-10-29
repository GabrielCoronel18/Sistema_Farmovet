const formularioCirugia = document.querySelector(".CirugiaForm");
const tablaCirugias = document.getElementById("TablaCirugias");
const tituloModal = document.getElementById("TituloModalCirugia");
const btnAgregar = document.getElementById("btnAgregar");
const filtrar = document.getElementById("filtrar");
const campoNombre = document.getElementById("nombre_cirugia");
const errorNombre = document.getElementById("error-nombre");
const campoGravedad = document.getElementById("gravedad");
const errorGravedad = document.getElementById("error-gravedad");
const campoEstado = document.getElementById("estado");
const errorEstado = document.getElementById("error-estado");
const limite = document.getElementById("limite");
const btnAnterior = document.getElementById("btn-anterior");
const btnSiguiente = document.getElementById("btn-siguiente");
const infoPagina = document.getElementById("info-pagina");
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
			tablaCirugias.innerHTML = "";
			if (resultado.status !== "success") {
				tablaCirugias.innerHTML = "<tr><td colspan='4'>No hay cirugías registradas</td></tr>";
				paginaActual = pagina;
				actualizarPaginacion(false);
				return;
			}
			if (resultado.resultados.length === 0 && pagina > 1) {
				obtenerDatos(param, pagina - 1);
				return;
			}

			resultado.resultados.forEach(cirugia => {
				tablaCirugias.innerHTML += `<tr>
					<td class="table-light">${cirugia.id_cirugia}</td>
					<td class="table-light">${cirugia.nombre_cirugia}</td>
					<td class="table-light">${cirugia.gravedad}</td>
					<td class="table-light">${cirugia.estado == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class=' badge bg-secondary'>Inactivo</span>"}</td>
					<td class="table-light">
						<button class="btn btn-sm btn-success btn-actualizar" value="${cirugia.id_cirugia}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
						<button class="btn btn-sm btn-danger btn-eliminar" value="${cirugia.id_cirugia}">Eliminar</button>
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
	formularioCirugia.reset();
	document.getElementById("id_cirugia").value = "";
	tituloModal.innerText = "Agregar Nueva Cirugía";
});

tablaCirugias.addEventListener("click", event => {
	if (event.target.classList.contains("btn-actualizar")) {
		const datos = new FormData();
		datos.append("obtenerCirugia", "true");
		datos.append("id", event.target.value);

		fetch(window.location, { method: "POST", body: datos })
			.then(respuesta => respuesta.json())
			.then(resultado => {
				if (resultado.status !== "success") return;
				const cirugia = resultado.resultado;
				document.getElementById("id_cirugia").value = cirugia.id_cirugia;
				document.getElementById("nombre_cirugia").value = cirugia.nombre_cirugia;
				document.getElementById("gravedad").value = cirugia.gravedad;
				const estadoInput = document.getElementById("estado");
				if (estadoInput) estadoInput.value = cirugia.estado;
				tituloModal.innerText = "Actualizar Cirugía";
			});
	}

	if (event.target.classList.contains("btn-eliminar")) {
		const datos = new FormData();
		datos.append("eliminar", "true");
		datos.append("id", event.target.value);
		alertEliminar("post", datos, () => obtenerDatos(filtrar.value, paginaActual));
	}
});

formularioCirugia.addEventListener("submit", event => {
	event.preventDefault();
	const datos = new FormData(formularioCirugia);
	const id = document.getElementById("id_cirugia").value;
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			if (resultado.status !== "success") {
				if (resultado.errors) {
					
					if (resultado.errors.nombre) {
						campoNombre.classList.add("is-invalid");
						errorNombre.textContent = resultado.errors.nombre;
					}
					else {
						campoNombre.classList.remove("is-invalid");
						errorNombre.textContent = "";
					}
					if (resultado.errors.gravedad) {
						campoGravedad.classList.add("is-invalid");
						errorGravedad.textContent = resultado.errors.gravedad;
					}
					else {
						campoGravedad.classList.remove("is-invalid");
						errorGravedad.textContent = "";
					}
					if (resultado.errors.estado) {
						campoEstado.classList.add("is-invalid");
						errorEstado.textContent = resultado.errors.estado;
					}
					else {
						campoEstado.classList.remove("is-invalid");
						errorEstado.textContent = "";
					}
				}
				Swal.fire({ title: "Error", text: "No se pudo guardar la cirugía", icon: "error" });
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
