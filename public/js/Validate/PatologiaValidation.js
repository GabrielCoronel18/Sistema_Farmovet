$(function () {
    $.validator.addMethod("nombrePatologia", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u.test(value.trim());
    }, "Solo se permiten letras, espacios, guiones, paréntesis y barras.");
    $.validator.addMethod("textoPatologia", function (value, element) {
        return this.optional(element) || /^[\p{L}\p{N}]+(?:[\s.,;:()\/'%-]+[\p{L}\p{N}]+)*$/u.test(value.trim());
    }, "El texto contiene caracteres no permitidos.");

    $(".PatologiaForm").validate({
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
            nombre: { required: true, minlength: 5, maxlength: 50, nombrePatologia: true },
            tipo: { required: true, minlength: 5, maxlength: 50, nombrePatologia: true },
            sintomas: { required: true, minlength: 5, maxlength: 200, textoPatologia: true }
        },
        messages: {
            nombre: {
                required: "El nombre es obligatorio.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "No puede exceder 50 caracteres.",
                nombrePatologia: "Solo se permiten letras, espacios, guiones, paréntesis y barras."
            },
            tipo: {
                required: "El tipo es obligatorio.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "No puede exceder 50 caracteres.",
                nombrePatologia: "Solo se permiten letras, espacios, guiones, paréntesis y barras."
            },
            sintomas: {
                required: "Los síntomas son obligatorios.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "No puede exceder 200 caracteres.",
                textoPatologia: "Los síntomas contienen caracteres no permitidos."
            }
        }
    });
});
