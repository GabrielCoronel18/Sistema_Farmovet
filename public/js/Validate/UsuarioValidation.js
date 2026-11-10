$(function () {
    $.validator.addMethod("cedulaUsuario", function (value, element) {
        return this.optional(element) || /^(?:\d{10,12}|[VEJGP]-\d{6,10})$/i.test(value.trim());
    }, "Use una cédula numérica o el formato V-12345678.");
    $.validator.addMethod("nombreUsuario", function (value, element) {
        return this.optional(element) || /^[\p{L}]+(?:[ '-]+[\p{L}]+)*$/u.test(value.trim());
    }, "Solo se permiten letras, espacios, apóstrofes y guiones.");
    $.validator.addMethod("telefonoUsuario", function (value, element) {
        return this.optional(element) || /^\d{9,11}$/.test(value.trim());
    }, "El teléfono debe contener entre 9 y 11 números.");
    $.validator.addMethod("contrasenaUsuario", function (value, element) {
        return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,72}$/.test(value);
    }, "Debe tener entre 8 y 72 caracteres, mayúscula, minúscula, número y símbolo.");

    $("#form-usuario").validate({
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
            cedula: { required: true, minlength: 8, maxlength: 12, cedulaUsuario: true },
            nombre: { required: true, minlength: 2, maxlength: 30, nombreUsuario: true },
            apellido: { required: true, minlength: 2, maxlength: 30, nombreUsuario: true },
            telefono: { telefonoUsuario: true },
            correo: { required: true, email: true, maxlength: 60 },
            "contraseña": { required: true, contrasenaUsuario: true },
            rol: { required: true }
        },
        messages: {
            cedula: {
                required: "La cédula es obligatoria.",
                minlength: "La cédula debe tener al menos 8 caracteres.",
                maxlength: "La cédula no puede exceder 12 caracteres.",
                cedulaUsuario: "Use una cédula numérica o el formato V-12345678."
            },
            nombre: {
                required: "El nombre es obligatorio.",
                minlength: "Debe tener al menos 2 caracteres.",
                maxlength: "No puede exceder 30 caracteres.",
                nombreUsuario: "Solo se permiten letras, espacios, apóstrofes y guiones."
            },
            apellido: {
                required: "El apellido es obligatorio.",
                minlength: "Debe tener al menos 2 caracteres.",
                maxlength: "No puede exceder 30 caracteres.",
                nombreUsuario: "Solo se permiten letras, espacios, apóstrofes y guiones."
            },
            telefono: "El teléfono debe contener entre 9 y 11 números.",
            correo: {
                required: "El correo es obligatorio.",
                email: "Ingrese un correo electrónico válido.",
                maxlength: "El correo no puede exceder 60 caracteres."
            },
            "contraseña": "Debe tener entre 8 y 72 caracteres, mayúscula, minúscula, número y símbolo.",
            rol: "Seleccione un rol."
        }
    });
});
