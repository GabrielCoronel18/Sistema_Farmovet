$(document).ready(function () {

    // Solo letras (con acentos, ñ) y espacios SOLO entre palabras
    $.validator.addMethod("soloLetras", function (value, element) {
        return this.optional(element) || /^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+(?:\s[a-zA-ZáéíóúÁÉÍÓÚñÑ]+)*$/.test(value.trim());
    }, "Solo se permiten letras y espacios entre palabras (no al inicio/final)");

    //  No permitir solo espacios
    $.validator.addMethod("noSoloEspacios", function (value, element) {
        return this.optional(element) || value.trim().length > 0;
    }, "El campo no puede contener solo espacios");

    //  No permitir dobles espacios
    $.validator.addMethod("sinDoblesEspacios", function (value, element) {
        return this.optional(element) || !/\s{2,}/.test(value);
    }, "No se permiten espacios dobles");

    $('#formularioEspecie').validate({
        onkeyup: function (element) {
            $(element).valid();
        },

        rules: {
            nombre_especie: {
                required: true,
                noSoloEspacios: true,      
                sinDoblesEspacios: true,   
                minlength: 3,
                maxlength: 50,
                soloLetras: true           // 🔒 regex estricta
            }
        },

        messages: {
            nombre_especie: {
                required: "El nombre de la especie es obligatorio",
                noSoloEspacios: "El nombre no puede ser solo espacios",
                sinDoblesEspacios: "No se permiten espacios dobles",
                minlength: "El nombre debe tener al menos 3 letras",
                maxlength: "El nombre no puede exceder 50 caracteres",
                soloLetras: "Solo se permiten letras",
            }
        }
    });
});