const patronNombreCliente = /^[\p{L}]+(?:[ '-]+[\p{L}]+)*$/u;
const patronDireccionCliente = /^[\p{L}\p{N}]+(?:[\s.,#()\/'-]+[\p{L}\p{N}]+)*$/u;
const patronCedulaCliente = /^(?:\d{10,12}|[VEJGP]-\d{6,10})$/i;
const patronCorreoCliente = /^[A-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?(?:\.[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?)+$/i;

function validarCampoCliente(id, errorId, patron, minimo, maximo, mensaje) {
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

function validarCliente() {
    const cedula = validarCampoCliente("cedula_cliente", "error-cedula", patronCedulaCliente,
        8, 12, "Use una cédula numérica o el formato V-12345678.");
    const nombre = validarCampoCliente("nombre", "error-nombre", patronNombreCliente,
        3, 30, "Debe tener entre 3 y 30 caracteres y solo contener letras, espacios, apóstrofes y guiones.");
    const apellido = validarCampoCliente("apellido", "error-apellido", patronNombreCliente,
        3, 30, "Debe tener entre 3 y 30 caracteres y solo contener letras, espacios, apóstrofes y guiones.");
    const telefono = validarCampoCliente("telefono", "error-telefono", /^\d{9,11}$/,
        9, 11, "El teléfono debe contener entre 9 y 11 números.");
    const correo = document.getElementById("correo");
    const correoValido = patronCorreoCliente.test(correo.value.trim());
    correo.classList.toggle("is-invalid", !correoValido);
    if (!correoValido) {
        document.getElementById("error-correo").textContent =
            "Ingrese un correo válido, por ejemplo: usuario@dominio.com.";
    }
    const direccion = validarCampoCliente("direccion", "error-direccion", patronDireccionCliente,
        10, 50, "Debe tener entre 10 y 50 caracteres y solo contener letras, números, espacios, puntos, comas, paréntesis y guiones.");
    return cedula && nombre && apellido && telefono && correoValido && direccion;
}
