$(function () {
    $.validator.addMethod("nombreMascota", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?:[ '-]+[\p{L}]+)*$/u.test(value.trim());
    }, "El nombre solo puede contener letras, espacios, apóstrofes y guiones.");
    $.validator.addMethod("pelajeMascota", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?:[ \/()-]+[\p{L}]+)*$/u.test(value.trim());
    }, "El pelaje solo puede contener letras, espacios, guiones, paréntesis y barras.");
    $.validator.addMethod("procedenciaMascota", function (value, element) {
        return this.optional(element) || /^[\p{L}\p{N}]+(?:[\s.,()\/'-]+[\p{L}\p{N}]+)*$/u.test(value.trim());
    }, "La procedencia contiene caracteres no permitidos.");

    $(".MascotasForm").validate({
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
            nombre: { required: true, minlength: 2, maxlength: 50, nombreMascota: true },
            edad: { required: true, digits: true, min: 0 },
            fch_nacimiento: { required: true, dateISO: true },
            sexo: { required: true },
            chip: { required: true },
            id_raza: { required: true },
            pelaje: { required: true, minlength: 4, maxlength: 50, pelajeMascota: true },
            cedula_cliente: { required: true },
            procedencia: { required: true, minlength: 5, maxlength: 200, procedenciaMascota: true }
        },
        messages: {
            nombre: {
                required: "El nombre es obligatorio.",
                minlength: "Debe tener al menos 2 caracteres.",
                maxlength: "No puede exceder 50 caracteres.",
                nombreMascota: "El nombre solo puede contener letras, espacios, apóstrofes y guiones."
            },
            edad: {
                required: "La edad es obligatoria.",
                digits: "Ingrese una edad entera.",
                min: "La edad no puede ser negativa."
            },
            fch_nacimiento: {
                required: "La fecha de nacimiento es obligatoria.",
                dateISO: "Ingrese una fecha válida."
            },
            sexo: "Seleccione el sexo.",
            chip: "Indique si la mascota tiene chip.",
            id_raza: "Seleccione una raza.",
            pelaje: {
                required: "El pelaje es obligatorio.",
                minlength: "Debe tener al menos 4 caracteres.",
                maxlength: "No puede exceder 50 caracteres.",
                pelajeMascota: "El pelaje solo puede contener letras, espacios, guiones, paréntesis y barras."
            },
            cedula_cliente: "Seleccione un cliente.",
            procedencia: {
                required: "La procedencia es obligatoria.",
                minlength: "Debe tener al menos 5 caracteres.",
                maxlength: "No puede exceder 200 caracteres.",
                procedenciaMascota: "La procedencia contiene caracteres no permitidos."
            }
        }
    });
});
