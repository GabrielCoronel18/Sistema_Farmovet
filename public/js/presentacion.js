const formularioPresentacion = document.querySelector(".PresentacionForm");
const tablaPresentaciones = document.getElementById("TablaPresentaciones");
const tituloModal = document.getElementById("TituloModalPresentacion");
const btnAgregar = document.getElementById("btnAgregar");
const filtrar = document.getElementById("filtrar");

function obtenerDatos(param = null) {
	const datos = new FormData();
	datos.append("obtener", "true");
	if (param !== null) datos.append("parametro", param);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			tablaPresentaciones.innerHTML = "";
			if (resultado.status !== "success") {
				tablaPresentaciones.innerHTML = "<tr><td colspan='3'>No hay presentaciones registradas</td></tr>";
				return;
			}

			resultado.resultados.forEach(presentacion => {
				tablaPresentaciones.innerHTML += `<tr>
					<td class="table-light">${presentacion.id_presentacion}</td>
					<td class="table-light">${presentacion.nombre_presentacion}</td>
					<td class="table-light">
						<button class="btn btn-sm btn-success btn-actualizar" value="${presentacion.id_presentacion}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
						<button class="btn btn-sm btn-danger btn-eliminar" value="${presentacion.id_presentacion}">Eliminar</button>
					</td>
				</tr>`;
			});
		});
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
				tituloModal.innerText = "Actualizar Presentación";
			});
	}

	if (event.target.classList.contains("btn-eliminar")) {
		const datos = new FormData();
		datos.append("eliminar", "true");
		datos.append("id", event.target.value);
		alertEliminar("post", datos, obtenerDatos);
	}
});

 formularioPresentacion.addEventListener("submit", event => {
	event.preventDefault();
	const datos = new FormData(formularioPresentacion);
	const id = document.getElementById("id_presentacion").value;
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			if (resultado.status !== "success") {
				Swal.fire({ title: "Error", text: "No se pudo guardar la presentación", icon: "error" });
				return;
			}
			id === "" ? alertAgregar("success") : alertActualizar("success");
			bootstrap.Modal.getInstance(document.getElementById("ModalAgregar")).hide();
			obtenerDatos();
		});
});

filtrar.addEventListener("input", () => obtenerDatos(filtrar.value));
