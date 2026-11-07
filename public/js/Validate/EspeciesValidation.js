const patronEspecie = /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u;

function validarNombreCatalogo(id, errorId, patron) {
    const campo = document.getElementById(id);
    const error = document.getElementById(errorId);
    const valor = campo.value.trim();
    campo.classList.remove("is-invalid");
    error.textContent = "";
    if (valor.length < 3 || valor.length > 50 || !patron.test(valor)) {
        campo.classList.add("is-invalid");
        error.textContent = "Debe tener entre 3 y 50 caracteres y solo contener letras, espacios, guiones, paréntesis y barras.";
        return false;
    }
    return true;
}

function validarEspecie() {
    return validarNombreCatalogo("nombre_especie", "error-nombre_especie", patronEspecie);
}
