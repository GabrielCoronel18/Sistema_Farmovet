$(document).ready(function () {
    $.validator.addMethod("soloLetras", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?: +[\p{L}]+)*$/u.test(value);
    }, "Solo se permiten letras y espacios.");

    $.validator.addMethod("soloNumeros", function (value, element) {
        return this.optional(element) || /^[0-9]+$/.test(value);
    }, "Solo se permiten números.");

    $("#formularioEspecie").validate({
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
            nombre_especie: {
                required: true,
                minlength: 3,
                maxlength: 50,
                normalizer: value => value.trim(),
                soloLetras: true
            }
        },
        messages: {
            nombre_especie: {
                required: "El nombre de la especie es obligatorio.",
                minlength: "El nombre debe tener al menos 3 caracteres.",
                maxlength: "El nombre no puede exceder 50 caracteres.",
                soloLetras: "Solo se permiten letras y espacios."
            }
        }
    });
});
