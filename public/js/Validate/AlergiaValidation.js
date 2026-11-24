$(function () {
  
   $.validator.addMethod("regexAlergia", function (value, element) {
        return this.optional(element) || /^(?=.*\p{L})[\p{L} ()\/-]+$/u.test(value);
    }, "Solo se permiten letras, espacios, guiones, paréntesis y barras.");
   
   
   $('#formAlergia').validate({
     
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
       rules:{
           nombre: {
               required: true,
               minlength: 3,
               maxlength: 80,
               normalizer: value => value.trim(),
               regexAlergia: true
           },
           tipo: {
               required: true,
               minlength: 3,
               maxlength: 80,
               normalizer: value => value.trim(),
               regexAlergia: true
           }
       },
       messages: {
              nombre: {
                   required: "El nombre de la alergia es obligatorio.",
                   minlength: "El nombre de la alergia debe tener al menos 3 caracteres.",
                   maxlength: "El nombre de la alergia no debe exceder los 80 caracteres.",
                   regexAlergia: "Solo se permiten letras, espacios, guiones, paréntesis y barras."
               },
               tipo: {
                   required: "El tipo de alergia es obligatorio.",
                   minlength: "El tipo de alergia debe tener al menos 3 caracteres.",
                   maxlength: "El tipo de alergia no debe exceder los 80 caracteres.",
                   regexAlergia: "Solo se permiten letras, espacios, guiones, paréntesis y barras."
               }
       }

   });

});