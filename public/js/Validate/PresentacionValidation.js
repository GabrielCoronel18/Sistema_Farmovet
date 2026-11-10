$(function () {
    $.validator.addMethod("nombrePresentacion", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?:[ ()\/-]+[\p{L}]+)*$/u.test(value.trim());
    }, "Solo se permiten letras, espacios, guiones, paréntesis y barras.");

    $(".PresentacionForm").validate({
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
                nombrePresentacion: true
            }
        },
        messages: {
            nombre: {
                required: "El nombre de la presentación es obligatorio.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "No puede exceder 50 caracteres.",
                nombrePresentacion: "Solo se permiten letras, espacios, guiones, paréntesis y barras."
            }
        }
    });
});
