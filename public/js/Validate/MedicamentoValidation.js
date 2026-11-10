$(function () {
    $.validator.addMethod("nombreMedicamento", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u.test(value.trim());
    }, "Solo se permiten letras, espacios, guiones, paréntesis y barras.");

    $(".MedicamentoForm").validate({
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
            nombre: {
                required: true,
                minlength: 5,
                maxlength: 50,
                nombreMedicamento: true
            },
            tipo: { required: true },
            presentacion: { required: true }
        },
        messages: {
            nombre: {
                required: "El nombre del medicamento es obligatorio.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "No puede exceder 50 caracteres.",
                nombreMedicamento: "Solo se permiten letras, espacios, guiones, paréntesis y barras."
            },
            tipo: "Seleccione un tipo de medicamento.",
            presentacion: "Seleccione una presentación."
        }
    });
});
