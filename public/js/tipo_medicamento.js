const formularioTipoMedicamento = document.querySelector(".TipoMedicamentoForm");
const tablaTipoMedicamentos = document.getElementById("TablaTipoMedicamentos");
const tituloModal = document.getElementById("TituloModalTipoMedicamento");
const btnAgregar = document.getElementById("btnAgregar");
const filtrar = document.getElementById("filtrar");
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
			tablaTipoMedicamentos.innerHTML = "";
			if (resultado.status !== "success") {
				tablaTipoMedicamentos.innerHTML = "<tr><td colspan='3'>No hay tipos de medicamentos registrados</td></tr>";
				paginaActual = pagina;
				actualizarPaginacion(0);
				return;
			}

			resultado.resultados.forEach(tipoMedicamento => {
				tablaTipoMedicamentos.innerHTML += `<tr>
					<td class="table-light">${tipoMedicamento.id_tipo_medicamento}</td>
					<td class="table-light">${tipoMedicamento.nom_tipo_medicamento}</td>
					<td class="table-light">
						<button class="btn btn-sm btn-success btn-actualizar" value="${tipoMedicamento.id_tipo_medicamento}" data-bs-toggle="modal" data-bs-target="#ModalAgregar">Actualizar</button>
						<button class="btn btn-sm btn-danger btn-eliminar" value="${tipoMedicamento.id_tipo_medicamento}">Eliminar</button>
					</td>
				</tr>`;
			});
			paginaActual = pagina;
			actualizarPaginacion(resultado.resultados.length);
		});
}

function actualizarPaginacion(registrosMostrados) {
	const limiteActual = Number(limite.value);
	btnAnterior.parentElement.classList.toggle("disabled", paginaActual === 1);
	btnAnterior.disabled = paginaActual === 1;
	btnSiguiente.disabled = registrosMostrados < limiteActual;
	infoPagina.innerText = `Página ${paginaActual}`;
}

obtenerDatos();

btnAgregar.addEventListener("click", () => {
	formularioTipoMedicamento.reset();
	document.getElementById("id_tipo_medicamento").value = "";
	tituloModal.innerText = "Agregar Nuevo Tipo de Medicamento";
});

tablaTipoMedicamentos.addEventListener("click", event => {
	if (event.target.classList.contains("btn-actualizar")) {
		const datos = new FormData();
		datos.append("obtenerTipoMedicamento", "true");
		datos.append("id", event.target.value);

		fetch(window.location, { method: "POST", body: datos })
			.then(respuesta => respuesta.json())
			.then(resultado => {
				if (resultado.status !== "success") return;
				const tipoMedicamento = resultado.resultado;
				document.getElementById("id_tipo_medicamento").value = tipoMedicamento.id_tipo_medicamento;
				document.getElementById("nombre").value = tipoMedicamento.nom_tipo_medicamento;
				tituloModal.innerText = "Actualizar Tipo de Medicamento";
			});
	}

	if (event.target.classList.contains("btn-eliminar")) {
		const datos = new FormData();
		datos.append("eliminar", "true");
		datos.append("id", event.target.value);
		alertEliminar("post", datos, () => obtenerDatos(filtrar.value, paginaActual));
	}
});

 formularioTipoMedicamento.addEventListener("submit", event => {
	event.preventDefault();
	const datos = new FormData(formularioTipoMedicamento);
	const id = document.getElementById("id_tipo_medicamento").value;
	datos.append(id === "" ? "agregar" : "actualizar", "true");
	if (id !== "") datos.append("id", id);

	fetch(window.location, { method: "POST", body: datos })
		.then(respuesta => respuesta.json())
		.then(resultado => {
			if (resultado.status !== "success") {
				Swal.fire({ title: "Error", text: "No se pudo guardar el tipo de medicamento", icon: "error" });
				return;
			}
			id === "" ? alertAgregar("success") : alertActualizar("success");
			bootstrap.Modal.getInstance(document.getElementById("ModalAgregar")).hide();
			obtenerDatos(filtrar.value, paginaActual);
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
