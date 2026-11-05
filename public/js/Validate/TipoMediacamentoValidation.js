const patronTipoMedicamento = /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u;

function validarNombreCatalogo(id, errorId, patron) {
    const campo = document.getElementById(id);
    const error = document.getElementById(errorId);
    const valor = campo.value.trim();
    campo.classList.remove("is-invalid");
    error.textContent = "";

    if (valor.length < 5 || valor.length > 50 || !patron.test(valor)) {
        campo.classList.add("is-invalid");
        error.textContent =
            "Debe tener entre 5 y 50 caracteres y solo contener letras, espacios, guiones, paréntesis y barras.";
        return false;
    }
    return true;
}

function validarTipoMedicamento() {
    return validarNombreCatalogo("nombre", "error-nombre", patronTipoMedicamento);
}

document.getElementById("nombre")?.addEventListener("input", () => {
    const campo = document.getElementById("nombre");
    if (campo.value.trim() !== "") validarTipoMedicamento();
});
