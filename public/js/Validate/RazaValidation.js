const patronNombreRaza = /^[\p{L}]+(?:[ '-]+[\p{L}]+)*$/u;

function validarRaza() {
    const campo = document.getElementById("nombre_raza");
    const especie = document.getElementById("id_especie");
    campo.classList.remove("is-invalid");
    especie.classList.remove("is-invalid");
    const error = document.getElementById("error-nombre-raza");
    const valor = campo.value.trim();
    error.textContent = "";

    if (valor.length < 2 || valor.length > 50 || !patronNombreRaza.test(valor)) {
        campo.classList.add("is-invalid");
        error.textContent = "Debe tener entre 2 y 50 caracteres y solo contener letras, espacios, apóstrofes y guiones.";
        return false;
    }
    if (!especie.value) {
        especie.classList.add("is-invalid");
        return false;
    }
    return true;
}
