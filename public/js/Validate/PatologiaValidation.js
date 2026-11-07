const patronNombrePatologia = /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u;
const patronTextoPatologia = /^[\p{L}\p{N}]+(?:[\s.,;:()\/'%-]+[\p{L}\p{N}]+)*$/u;

function validarNombreCatalogo(id, errorId, patron) {
    const campo = document.getElementById(id);
    const error = document.getElementById(errorId);
    const valor = campo.value.trim();
    campo.classList.remove("is-invalid");
    error.textContent = "";
    if (valor.length < 5 || valor.length > 50 || !patron.test(valor)) {
        campo.classList.add("is-invalid");
        error.textContent = "Debe tener entre 5 y 50 caracteres y solo contener letras, espacios, guiones, paréntesis y barras.";
        return false;
    }
    return true;
}

function validarPatologia() {
    const nombreValido = validarNombreCatalogo("nombre", "error-nombre", patronNombrePatologia);
    const tipoValido = validarCampoPatologia(
        "tipo", "error-tipo", patronNombrePatologia, 5, 50,
        " Debe tener entre 5 y 50 caracteres y solo contener letras, espacios, guiones, paréntesis y barras."
    );
    const sintomasValidos = validarCampoPatologia(
        "sintomas", "error-sintomas", patronTextoPatologia, 5, 200,
        "Los síntomas contienen caracteres no permitidos o no cumplen con la longitud requerida."
    );
    return nombreValido && tipoValido && sintomasValidos;
}

function validarCampoPatologia(id, errorId, patron, minimo, maximo, mensaje) {
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
