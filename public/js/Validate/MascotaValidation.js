const patronNombreMascota = /^[\p{L}]+(?:[ '-]+[\p{L}]+)*$/u;
const patronPelajeMascota = /^[\p{L}]+(?:[ \/()-]+[\p{L}]+)*$/u;
const patronProcedenciaMascota = /^[\p{L}\p{N}]+(?:[\s.,()\/'-]+[\p{L}\p{N}]+)*$/u;

function validarCampoMascota(id, errorId, patron, minimo, maximo, mensaje) {
    const campo = document.getElementById(id);
    const error = document.getElementById(errorId);
    const valor = campo.value.trim();
    campo.classList.remove("is-invalid");
    error.textContent = "";

    if (valor.length < minimo || valor.length > maximo || !patron.test(valor)) {
        campo.classList.add("is-invalid");
        error.textContent = mensaje;
        return false;
    }
    return true;
}

function validarMascota() {
    const nombreValido = validarCampoMascota(
        "nombre", "error-nombre", patronNombreMascota, 2, 50,
        "El nombre solo puede contener letras, espacios, apóstrofes y guiones."
    );
    const pelajeValido = validarCampoMascota(
        "pelaje", "error-pelaje", patronPelajeMascota, 4, 50,
        "El pelaje solo puede contener letras, espacios, guiones, paréntesis y barras."
    );
    const procedenciaValida = validarCampoMascota(
        "procedencia", "error-procedencia", patronProcedenciaMascota, 5, 200,
        "La procedencia contiene caracteres no permitidos."
    );
    return nombreValido && pelajeValido && procedenciaValida;
}
