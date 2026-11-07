const patronCedulaUsuario = /^(?:\d{10,12}|[VEJGP]-\d{6,10})$/i;
const patronNombreUsuario = /^[\p{L}]+(?:[ '-]+[\p{L}]+)*$/u;
const patronTelefonoUsuario = /^\d{9,11}$/;
const patronContraseñaUsuario = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,72}$/;

function validarCampoUsuario(id, patron, minimo, maximo, mensaje, opcional = false) {
    const campo = document.getElementById(id);
    const error = campo.parentElement.querySelector(".invalid-feedback");
    const valor = campo.value.trim();

    campo.classList.remove("is-invalid");
    if (error) error.textContent = "";

    if (opcional && valor === "") return true;

    if (
        valor.length < minimo ||
        valor.length > maximo ||
        !patron.test(valor)
    ) {
        campo.classList.add("is-invalid");
        if (error) error.textContent = mensaje;
        return false;
    }

    return true;
}

function validarCedulaUsuario() {
    return validarCampoUsuario(
        "cedula",
        patronCedulaUsuario,
        8,
        12,
        "Use una cédula numérica o el formato V-12345678."
    );
}

function validarNombreUsuario(id, etiqueta) {
    return validarCampoUsuario(
        id,
        patronNombreUsuario,
        2,
        30,
        `Ingrese un ${etiqueta} válido.`
    );
}

function validarTelefonoUsuario() {
    return validarCampoUsuario(
        "telefono",
        patronTelefonoUsuario,
        9,
        11,
        "El teléfono debe contener entre 9 y 11 números.",
        true
    );
}

function validarCorreoUsuario() {
    const campo = document.getElementById("correo");
    const error = campo.parentElement.querySelector(".invalid-feedback");
    const valido = campo.checkValidity();

    campo.classList.toggle("is-invalid", !valido);
    if (error) error.textContent = valido
        ? ""
        : "Ingrese un correo electrónico válido.";
    return valido;
}

function validarContraseñaUsuario() {
    const campo = document.getElementById("contraseña");
    const valor = campo.value;
    const esEdicion = !campo.required && valor === "";
    const error = campo.parentElement.querySelector(".invalid-feedback");

    campo.classList.remove("is-invalid");
    if (error) error.textContent = "";
    if (esEdicion) return true;

    const valido = patronContraseñaUsuario.test(valor);
    if (!valido) {
        campo.classList.add("is-invalid");
        if (error) {
            error.textContent =
                "Debe tener entre 8 y 72 caracteres, mayúscula, minúscula, número y símbolo.";
        }
    }
    return valido;
}

function validarRolUsuario() {
    const campo = document.getElementById("rol");
    const error = campo.parentElement.querySelector(".invalid-feedback");
    const valido = campo.value !== "";

    campo.classList.toggle("is-invalid", !valido);
    if (error && !valido) error.textContent = "Seleccione un rol.";
    return valido;
}

function validarUsuario() {
    const cedula = validarCedulaUsuario();
    const nombre = validarNombreUsuario("nombre", "nombre");
    const apellido = validarNombreUsuario("apellido", "apellido");
    const telefono = validarTelefonoUsuario();
    const correo = validarCorreoUsuario();
    const contraseña = validarContraseñaUsuario();
    const rol = validarRolUsuario();

    return cedula && nombre && apellido && telefono &&
        correo && contraseña && rol;
}
