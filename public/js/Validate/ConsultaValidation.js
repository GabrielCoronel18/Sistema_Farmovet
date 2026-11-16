$(function () {
    const formulario = $("[data-consulta-form]");
    if (!formulario.length) return;

    $.validator.addMethod("pesoConsulta", function (value, element) {
        return this.optional(element) || (Number.isFinite(Number(value)) && Number(value) >= 0);
    }, "Ingrese un peso válido mayor o igual a cero.");

    formulario.validate({
        ignore: [],
        focusInvalid: false,
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
        invalidHandler: (event, validator) => {
            const campo = validator.errorList[0]?.element;
            const panel = campo?.closest(".tab-pane");
            if (!panel) return;
            const tab = document.querySelector(`[data-bs-target="#${panel.id}"]`);
            if (tab) bootstrap.Tab.getOrCreateInstance(tab).show();
        },
        rules: (() => {
            const reglas = {
            id_mascota: { required: true },
            fecha: { required: true, dateISO: true },
            tipo_ingreso: { required: true, digits: true, range: [1, 2] },
            remitido: { required: true, digits: true, range: [0, 1] },
            peso_consulta: { pesoConsulta: true },
            pronostico: { maxlength: 100 },
            tratamiento_consulta: { maxlength: 80 },
            comentario_seguimiento: { maxlength: 200 },
            motivo_consulta: { maxlength: 200 },
            examenes_efectuados: { maxlength: 200 },
            tratamientos_realizados: { maxlength: 200 },
            inicio_enfermedad: { dateISO: true },
            ult_desp_interna: { dateISO: true },
            ult_desp_externa: { dateISO: true },
            ultima_vacunacion: { dateISO: true },
            fch_ultimo_celo: { dateISO: true },
            ant_ectoparasitos: { digits: true, range: [0, 1] },
            act_ectoparasitos: { digits: true, range: [0, 1] },
            ult_desp_int_producto: { maxlength: 80 },
            ult_desp_ext_producto: { maxlength: 80 },
            tipo_alimentacion: { maxlength: 80 },
            frec_alimentacion: { maxlength: 80 },
            apetito: { maxlength: 80 },
            ingesta_agua: { maxlength: 80 },
            contacto_animal: { maxlength: 80 },
            vomito: { maxlength: 80 },
            heces: { maxlength: 80 },
            miccion: { maxlength: 80 },
            prod_higiene: { maxlength: 80 },
            frec_higiene: { maxlength: 80 },
            int_ambiente: { maxlength: 80 },
            ext_ambiente: { maxlength: 80 },
            peso: { number: true, min: 0 },
            cc: { maxlength: 80 },
            temp_celsius: { number: true },
            pulso_yugular: { maxlength: 80 },
            fr_rpm: { number: true },
            fc_lpm: { number: true },
            pulso_ppm: { number: true },
            tlc_seg: { number: true },
            tpc_seg: { number: true },
            pas: { number: true, maxlength: 40 },
            pad: { number: true, maxlength: 40 },
            prcnt_deshidratacion: { number: true },
            gangliios_palpables: { maxlength: 100 },
            mucosas_visibles: { maxlength: 100 },
            ectoparasitos: { maxlength: 100 },
            actitud: { maxlength: 80 },
            hallazgos: { maxlength: 400 },
            hematologia_completa: { maxlength: 300 },
            coprologia: { maxlength: 300 },
            quimica_sanguinea: { maxlength: 300 },
            uro_urob: { maxlength: 50 },
            uro_bli: { maxlength: 50 },
            uro_prot: { maxlength: 50 },
            uro_nitritos: { maxlength: 50 },
            uro_cetona: { maxlength: 50 },
            uro_glucosa: { maxlength: 50 },
            uro_ph: { maxlength: 50 },
            uro_leu: { maxlength: 50 },
            uro_densidad: { maxlength: 50 },
            uro_microorganismos: { maxlength: 50 },
            uro_celulas: { maxlength: 50 },
            uro_cilindros: { maxlength: 50 },
            uro_cristales: { maxlength: 50 },
            descarte: { maxlength: 50 },
            piel_otros: { maxlength: 50 },
            snap: { maxlength: 50 },
            observaciones: { maxlength: 150 }
            };
            Object.values(reglas).forEach(regla => {
                if (Number.isInteger(regla.maxlength)) {
                    regla.normalizer = value => value.trim();
                }
            });
            return reglas;
        })(),
        messages: {
            id_mascota: "Seleccione una mascota.",
            fecha: {
                required: "La fecha es obligatoria.",
                dateISO: "Ingrese una fecha válida."
            },
            tipo_ingreso: {
                required: "Seleccione el tipo de ingreso.",
                digits: "Seleccione un tipo de ingreso válido.",
                range: "Seleccione un tipo de ingreso válido."
            },
            remitido: {
                required: "Indique si la consulta fue remitida.",
                digits: "Seleccione una opción válida.",
                range: "Seleccione una opción válida."
            },
            peso_consulta: "Ingrese un peso válido mayor o igual a cero.",
            pronostico: { maxlength: "El pronóstico no debe exceder los 100 caracteres." },
            tratamiento_consulta: { maxlength: "El tratamiento no debe exceder los 80 caracteres." },
            comentario_seguimiento: { maxlength: "El comentario no debe exceder los 200 caracteres." },
            motivo_consulta: { maxlength: "El motivo no debe exceder los 200 caracteres." },
            examenes_efectuados: { maxlength: "Los exámenes no deben exceder los 200 caracteres." },
            tratamientos_realizados: { maxlength: "Los tratamientos no deben exceder los 200 caracteres." },
            inicio_enfermedad: { dateISO: "Ingrese una fecha de inicio válida." },
            ult_desp_interna: { dateISO: "Ingrese una fecha válida." },
            ult_desp_externa: { dateISO: "Ingrese una fecha válida." },
            ultima_vacunacion: { dateISO: "Ingrese una fecha de vacunación válida." },
            fch_ultimo_celo: { dateISO: "Ingrese una fecha válida." },
            ant_ectoparasitos: {
                digits: "Seleccione Sí, No o deje el campo sin información.",
                range: "Seleccione Sí, No o deje el campo sin información."
            },
            act_ectoparasitos: {
                digits: "Seleccione Sí, No o deje el campo sin información.",
                range: "Seleccione Sí, No o deje el campo sin información."
            },
            ult_desp_int_producto: { maxlength: "El producto no debe exceder los 80 caracteres." },
            ult_desp_ext_producto: { maxlength: "El producto no debe exceder los 80 caracteres." },
            tipo_alimentacion: { maxlength: "La alimentación no debe exceder los 80 caracteres." },
            frec_alimentacion: { maxlength: "La frecuencia no debe exceder los 80 caracteres." },
            apetito: { maxlength: "El apetito no debe exceder los 80 caracteres." },
            ingesta_agua: { maxlength: "La ingesta de agua no debe exceder los 80 caracteres." },
            contacto_animal: { maxlength: "El contacto con animales no debe exceder los 80 caracteres." },
            vomito: { maxlength: "El vómito no debe exceder los 80 caracteres." },
            heces: { maxlength: "Las heces no deben exceder los 80 caracteres." },
            miccion: { maxlength: "La micción no debe exceder los 80 caracteres." },
            prod_higiene: { maxlength: "El producto de higiene no debe exceder los 80 caracteres." },
            frec_higiene: { maxlength: "La frecuencia de higiene no debe exceder los 80 caracteres." },
            int_ambiente: { maxlength: "El ambiente interno no debe exceder los 80 caracteres." },
            ext_ambiente: { maxlength: "El ambiente externo no debe exceder los 80 caracteres." },
            peso: {
                number: "Ingrese un peso válido.",
                min: "El peso no puede ser negativo."
            },
            cc: { maxlength: "La condición corporal no debe exceder los 80 caracteres." },
            temp_celsius: { number: "Ingrese una temperatura válida." },
            pulso_yugular: { maxlength: "El pulso yugular no debe exceder los 80 caracteres." },
            fr_rpm: { number: "Ingrese una frecuencia respiratoria válida." },
            fc_lpm: { number: "Ingrese una frecuencia cardíaca válida." },
            pulso_ppm: { number: "Ingrese un pulso válido." },
            tlc_seg: { number: "Ingrese un TLC válido." },
            tpc_seg: { number: "Ingrese un TPC válido." },
            pas: {
                number: "Ingrese una presión sistólica válida.",
                maxlength: "La presión sistólica no debe exceder los 40 caracteres."
            },
            pad: {
                number: "Ingrese una presión diastólica válida.",
                maxlength: "La presión diastólica no debe exceder los 40 caracteres."
            },
            prcnt_deshidratacion: { number: "Ingrese un porcentaje de deshidratación válido." },
            gangliios_palpables: { maxlength: "Los ganglios palpables no deben exceder los 100 caracteres." },
            mucosas_visibles: { maxlength: "Las mucosas visibles no deben exceder los 100 caracteres." },
            ectoparasitos: { maxlength: "Los ectoparásitos no deben exceder los 100 caracteres." },
            actitud: { maxlength: "La actitud no debe exceder los 80 caracteres." },
            hallazgos: { maxlength: "Los hallazgos no deben exceder los 400 caracteres." },
            hematologia_completa: { maxlength: "La hematología no debe exceder los 300 caracteres." },
            coprologia: { maxlength: "La coprología no debe exceder los 300 caracteres." },
            quimica_sanguinea: { maxlength: "La química sanguínea no debe exceder los 300 caracteres." },
            uro_urob: { maxlength: "El urobilinógeno no debe exceder los 50 caracteres." },
            uro_bli: { maxlength: "La bilirrubina no debe exceder los 50 caracteres." },
            uro_prot: { maxlength: "Las proteínas no deben exceder los 50 caracteres." },
            uro_nitritos: { maxlength: "Los nitritos no deben exceder los 50 caracteres." },
            uro_cetona: { maxlength: "Las cetonas no deben exceder los 50 caracteres." },
            uro_glucosa: { maxlength: "La glucosa no debe exceder los 50 caracteres." },
            uro_ph: { maxlength: "El pH no debe exceder los 50 caracteres." },
            uro_leu: { maxlength: "Los leucocitos no deben exceder los 50 caracteres." },
            uro_densidad: { maxlength: "La densidad no debe exceder los 50 caracteres." },
            uro_microorganismos: { maxlength: "Los microorganismos no deben exceder los 50 caracteres." },
            uro_celulas: { maxlength: "Las células no deben exceder los 50 caracteres." },
            uro_cilindros: { maxlength: "Los cilindros no deben exceder los 50 caracteres." },
            uro_cristales: { maxlength: "Los cristales no deben exceder los 50 caracteres." },
            descarte: { maxlength: "El descarte no debe exceder los 50 caracteres." },
            piel_otros: { maxlength: "El campo piel u otros no debe exceder los 50 caracteres." },
            snap: { maxlength: "El SNAP no debe exceder los 50 caracteres." },
            observaciones: { maxlength: "Las observaciones no deben exceder los 150 caracteres." }
        }
    });

});
