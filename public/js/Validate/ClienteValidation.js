$(document).ready(function () {

   
    // ✅ Solo letras (con acentos, ñ) y espacios SOLO entre palabras
    $.validator.addMethod("soloLetras", function (value, element) {
        return this.optional(element) || /^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+(?:\s[a-zA-ZáéíóúÁÉÍÓÚñÑ]+)*$/.test(value.trim());
    }, "Solo se permiten letras y espacios entre palabras");

    // ✅ No permitir solo espacios
    $.validator.addMethod("noSoloEspacios", function (value, element) {
        return this.optional(element) || value.trim().length > 0;
    }, "El campo no puede contener solo espacios");

    // ✅ No permitir dobles espacios
    $.validator.addMethod("sinDoblesEspacios", function (value, element) {
        return this.optional(element) || !/\s{2,}/.test(value);
    }, "No se permiten espacios dobles");

    // ✅ Cédula: SOLO números, longitud entre 6 y 9 dígitos (ej. 12345678)
    $.validator.addMethod("formatoCedula", function (value, element) {
        return this.optional(element) || /^\d{6,9}$/.test(value.trim());
    }, "La cédula debe contener solo números");

    // ✅ Teléfono: Solo números, permite + y guiones
    $.validator.addMethod("formatoTelefono", function (value, element) {
        return this.optional(element) || /^[\+]?[0-9\s\-]{10,15}$/.test(value.trim());
    }, "Formato de teléfono inválido");

    // ✅ Dirección: Letras, números, espacios, #, ., ,, -
    $.validator.addMethod("formatoDireccion", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ#.,\-\s]+$/.test(value.trim());
    }, "La dirección contiene caracteres no válidos");

    // =============================================
    // 2. VALIDACIÓN DEL FORMULARIO
    // =============================================

    $('.ClienteForm').validate({
        onkeyup: function (element) {
            $(element).valid();
        },

        rules: {
            cedula_cliente: {
                required: true,
                noSoloEspacios: true,
                formatoCedula: true, // <-- Aquí se aplica la nueva regla de puros números
                minlength: 6,
                maxlength: 9
            },
            nombre: {
                required: true,
                noSoloEspacios: true,
                sinDoblesEspacios: true,
                soloLetras: true,
                minlength: 2,
                maxlength: 30
            },
            apellido: {
                required: true,
                noSoloEspacios: true,
                sinDoblesEspacios: true,
                soloLetras: true,
                minlength: 2,
                maxlength: 30
            },
            telefono: {
                required: true,
                noSoloEspacios: true,
                formatoTelefono: true,
                minlength: 10,
                maxlength: 11
            },
            correo: {
                required: true,
                email: true,
                noSoloEspacios: true,
                maxlength: 100
            },
            direccion: {
                required: true,
                noSoloEspacios: true,
                sinDoblesEspacios: true,
                formatoDireccion: true,
                minlength: 5,
                maxlength: 50
            }
        },

        messages: {
            cedula_cliente: {
                required: "La cédula es obligatoria",
                formatoCedula: "La cédula debe contener solo números",
                minlength: "La cédula debe tener al menos 6 números",
                maxlength: "La cédula no puede tener más de 9 números"
            },
            nombre: {
                required: "El nombre es obligatorio",
                soloLetras: "Solo se permiten letras",
                minlength: "El nombre debe tener al menos 2 letras"
            },
            apellido: {
                required: "El apellido es obligatorio",
                soloLetras: "Solo se permiten letras",
                minlength: "El apellido debe tener al menos 2 letras"
            },
            telefono: {
                required: "El teléfono es obligatorio",
                formatoTelefono: "Ingrese un teléfono válido (Ej: 04241234567)",
                minlength: "El teléfono debe tener al menos 11 dígitos"
            },
            correo: {
                required: "El correo es obligatorio",
                email: "Ingrese un correo electrónico válido (Ej: correo@dominio.com)"
            },
            direccion: {
                required: "La dirección es obligatoria",
                formatoDireccion: "La dirección contiene caracteres no permitidos",
                minlength: "La dirección es muy corta"
            }
        }
    });
});