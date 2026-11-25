$(document).ready(function () {

    // =============================================
    // 1. MÉTODOS DE VALIDACIÓN PERSONALIZADOS
    // =============================================

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

    // =============================================
    // 2. VALIDACIÓN DEL FORMULARIO
    // =============================================

    $('#RazaForm').validate({
        onkeyup: function (element) {
            $(element).valid();
        },

        rules: {
            nombre_raza: {
                required: true,
                noSoloEspacios: true,
                sinDoblesEspacios: true,
                minlength: 2,
                maxlength: 50,
                soloLetras: true
            },
            id_especie: {
                required: true
            }
        },

        messages: {
            nombre_raza: {
                required: "El nombre de la raza es obligatorio",
                noSoloEspacios: "El nombre no puede ser solo espacios",
                sinDoblesEspacios: "No se permiten espacios dobles",
                minlength: "El nombre debe tener al menos 2 letras",
                maxlength: "El nombre no puede exceder 50 caracteres",
                soloLetras: "Solo se permiten letras"
            },
            id_especie: {
                required: "Debe seleccionar una especie"
            }
        }
    });
});