const formularioCirugia = document.querySelector(".CirugiaForm");
const tablaCirugias = document.getElementById("TablaCirugias");
const tituloModal = document.getElementById("TituloModalCirugia");
const btnAgregar = document.getElementById("btnAgregar");
const filtrar = document.getElementById("filtrar");

function obtenerDatos(param = null) {
	const datos = new FormData();
	datos.append("obtener", "true");
	if (param !== null) datos.append("parametro", param);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			tablaCirugias.innerHTML = "";
			if (resultado.status !== "success") {
				tablaCirugias.innerHTML = "<tr><td colspan='4'>No hay cirugías registradas</td></tr>";
				return;
			}

			resultado.resultados.forEach(cirugia => {
				tablaCirugias.innerHTML += `<tr>
					<td class="table-light">${cirugia.id_cirugia}</td>
					<td class="table-light">${cirugia.nombre_cirugia}</td>
					<td class="table-light">${cirugia.gravedad}</td>
					<td class="table-light">
						<button class="btn btn-sm btn-success btn-actualizar" value="${cirugia.id_cirugia}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
						<button class="btn btn-sm btn-danger btn-eliminar" value="${cirugia.id_cirugia}">Eliminar</button>
					</td>
				</tr>`;
			});
		});
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
				tituloModal.innerText = "Actualizar Cirugía";
			});
	}

	if (event.target.classList.contains("btn-eliminar")) {
		const datos = new FormData();
		datos.append("eliminar", "true");
		datos.append("id", event.target.value);
		alertEliminar("post", datos, obtenerDatos);
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
				Swal.fire({ title: "Error", text: "No se pudo guardar la cirugía", icon: "error" });
				return;
			}
			id === "" ? alertAgregar("success") : alertActualizar("success");
			bootstrap.Modal.getInstance(document.getElementById("ModalAgregar")).hide();
			obtenerDatos();
		});
});

filtrar.addEventListener("input", () => obtenerDatos(filtrar.value));
