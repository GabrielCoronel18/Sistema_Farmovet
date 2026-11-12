$(function () {
    const formulario = $("[data-consulta-form]");
    if (!formulario.length) return;

    $.validator.addMethod("pesoConsulta", function (value, element) {
        return this.optional(element) || (Number.isFinite(Number(value)) && Number(value) >= 0);
    }, "Ingrese un peso válido mayor o igual a cero.");

    formulario.validate({
        ignore: [],
        focusInvalid: false,
        onkeyup: element => $(element).valid(),
        onfocusout: element => $(element).valid(),
        errorElement: "div",
        errorClass: "invalid-feedback d-block",
        highlight: element => $(element).addClass("is-invalid"),
        unhighlight: element => {
            $(element).removeClass("is-invalid")
                .siblings(".invalid-feedback").empty().removeClass("d-block");
        },
        errorPlacement: (error, element) => {
            const feedback = element.siblings(".invalid-feedback").first();
            if (feedback.length) feedback.text(error.text()).addClass("d-block");
            else error.insertAfter(element);
        },
        invalidHandler: (event, validator) => {
            const campo = validator.errorList[0]?.element;
            const panel = campo?.closest(".tab-pane");
            if (!panel) return;
            const tab = document.querySelector(`[data-bs-target="#${panel.id}"]`);
            if (tab) bootstrap.Tab.getOrCreateInstance(tab).show();
        },
        rules: {
            id_mascota: { required: true },
            fecha: { required: true, dateISO: true },
            tipo_ingreso: { required: true, digits: true, range: [1, 2] },
            remitido: { required: true, digits: true, range: [0, 1] },
            peso_consulta: { pesoConsulta: true },
            pronostico: { maxlength: 100 },
            tratamiento_consulta: { maxlength: 100 },
            comentario_seguimiento: { maxlength: 200 }
        },
        messages: {
            id_mascota: "Seleccione una mascota.",
            fecha: {
                required: "La fecha es obligatoria.",
                dateISO: "Ingrese una fecha válida."
            },
            tipo_ingreso: "Seleccione un tipo de ingreso válido.",
            remitido: "Indique si la consulta fue remitida.",
            peso_consulta: "Ingrese un peso válido mayor o igual a cero.",
            pronostico: "El pronóstico no debe exceder los 100 caracteres.",
            tratamiento_consulta: "El tratamiento no debe exceder los 100 caracteres.",
            comentario_seguimiento: "El comentario no debe exceder los 200 caracteres."
        }
    });
});
