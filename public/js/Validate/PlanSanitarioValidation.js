function obtenerCampoPlan(formulario, nombre) {
    return formulario.querySelector(`[name="${nombre}"]`);
}

function validarIdPlanSanitario(formulario, nombre) {
    const campo = obtenerCampoPlan(formulario, nombre);
    const valor = campo.value.trim();
    campo.classList.remove("is-invalid");

    if (!/^[1-9]\d*$/.test(valor)) {
        campo.classList.add("is-invalid");
        return false;
    }
    return true;
}

function validarFechaPlanSanitario(formulario, nombre) {
    const campo = obtenerCampoPlan(formulario, nombre);
    campo.classList.remove("is-invalid");

    if (!campo.value) {
        campo.classList.add("is-invalid");
        return false;
    }
    return true;
}

function validarRangoFechasPlanSanitario(formulario) {
    const aplicacion = obtenerCampoPlan(formulario, "fecha_aplicacion");
    const refuerzo = obtenerCampoPlan(formulario, "proximo_refuerzo");
    const valido = aplicacion.value && refuerzo.value &&
        refuerzo.value >= aplicacion.value;

    if (!valido) refuerzo.classList.add("is-invalid");
    return Boolean(valido);
}

function validarPlanSanitario(formulario) {
    const mascota = validarIdPlanSanitario(formulario, "id_mascota");
    const medicamento = validarIdPlanSanitario(formulario, "id_medicamento");
    const aplicacion = validarFechaPlanSanitario(formulario, "fecha_aplicacion");
    const refuerzo = validarFechaPlanSanitario(formulario, "proximo_refuerzo");
    const rango = validarRangoFechasPlanSanitario(formulario);

    return mascota && medicamento && aplicacion && refuerzo && rango;
}
