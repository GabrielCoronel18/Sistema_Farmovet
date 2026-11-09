$(document).ready(function(){

    $.validator.addMethod("soloLetras", function(value, element) {
        return this.optional(element)  || /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(value);
      },  "Solo se permiten letras");


      $.validator.addMethod("soloNumeros", function(value,element){
         return this.optional(element) || /^[0-9]+$/.test(value);
         }, "solo se permiten números");


         $('#formularioEspecie').validate({
            onkeyup: function(element){
                $(element).valid();
            },

            onfocusout: function(element){
                $(element).valid();
            },
            
            rules: {
                nombre_especie: {
                    required: true,
                    minlength: 3,
                    maxlength: 50,
                    soloLetras: true
                }
            },
                
            messages: {
                nombre_especie: {
                    required: "El nombre de la especie es obligatorio",
                    minlength: "El nombre debe tener al menos 3 letras",  
                    maxlength: "El nombre no puede exceder 50 caracteres", 
                    soloLetras: " Solo se permiten letras"
                }
            }

        });
});