$(function () {
    $.validator.addMethod("fechaRefuerzo", function (value, element) {
        const formulario = element.form;
        const aplicacion = formulario.querySelector('[name="fecha_aplicacion"]');
        return this.optional(element) || !aplicacion.value || value >= aplicacion.value;
    }, "El refuerzo debe ser igual o posterior a la aplicación.");

    $("#form-modal-plan, #form-edicion-plan").each(function () {
        $(this).validate({
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
            rules: {
                id_mascota: { required: true, digits: true, min: 1 },
                id_medicamento: { required: true, digits: true, min: 1 },
                fecha_aplicacion: { required: true, dateISO: true },
                proximo_refuerzo: { required: true, dateISO: true, fechaRefuerzo: true }
            },
            messages: {
                id_mascota: "Ingrese un ID de mascota válido.",
                id_medicamento: "Ingrese un ID de medicamento válido.",
                fecha_aplicacion: {
                    required: "Ingrese una fecha válida.",
                    dateISO: "Ingrese una fecha válida."
                },
                proximo_refuerzo: {
                    required: "Ingrese una fecha válida.",
                    dateISO: "Ingrese una fecha válida.",
                    fechaRefuerzo: "El refuerzo debe ser igual o posterior a la aplicación."
                }
            }
        });
    });
});
