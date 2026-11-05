const patronRol = /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u;

function validarNombreCatalogo(id, errorId, patron) {
    const campo = document.getElementById(id);
    const error = document.getElementById(errorId);
    const valor = campo.value.trim();
    campo.classList.remove("is-invalid");
    error.textContent = "";
    if (valor.length < 3 || valor.length > 30 || !patron.test(valor)) {
        campo.classList.add("is-invalid");
        error.textContent = "Debe tener entre 3 y 30 caracteres y solo contener letras, espacios, guiones, paréntesis y barras.";
        return false;
    }
    return true;
}

function validarRol() {
    return validarNombreCatalogo("nombre", "error-nombre", patronRol);
}
