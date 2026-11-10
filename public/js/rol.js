const parametros = new URLSearchParams(window.location.search);
const accion = parametros.get("accion") || "crear";
const id = parametros.get("id");
const campoNombre = $("#nombre");
const errorNombre = $("#error-nombre");

if (accion === "editar" && id) {
	$("#titulo-form").text("Editar Rol");
	$("#btn-submit").html('<i class="bi bi-save"></i> Actualizar');
	const datos = new FormData();
	datos.append("obtenerRol", "1");
	datos.append("id", id);

	peticionAjax(datos, null, null, "?url=Rol").then(resultado => {
		if (resultado.status === "success") {
			$("#id").val(resultado.resultado.id_rol);
			campoNombre.val(resultado.resultado.nombre_rol);
			return;
		}
		Swal.fire("Error", "No se encontró el rol", "error")
			.then(() => window.location.href = "?url=Rol");
	});
}

$("#form-rol").on("submit", function (event) {
	event.preventDefault();
	if (!$(this).valid()) return;
	const datos = new FormData(this);
	datos.append(accion === "editar" ? "actualizar" : "agregar", "1");

	peticionAjax(datos, null, null, "?url=Rol").then(resultado => {
		if (resultado.status === "success") {
			Swal.fire({
				icon: "success",
				title: accion === "crear" ? "¡Rol creado!" : "¡Rol actualizado!",
				timer: 2000,
				showConfirmButton: false
			}).then(() => window.location.href = "?url=Rol");
			return;
		}
		if (resultado.errors && resultado.errors.nombre) {
			errorNombre.text(resultado.errors.nombre);
			campoNombre.addClass("is-invalid");
		}
		Swal.fire("Error", resultado.mensaje || "Operación fallida", "error");
	});
});
