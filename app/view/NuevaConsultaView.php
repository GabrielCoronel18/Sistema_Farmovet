
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Nueva consulta</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>
<div class="d-flex">
    <?php require_once __DIR__ . '/componente/menu.php'; ?>

    <main class="main-content w-100 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-purple mb-0">Nueva consulta</h2>
                <p class="text-muted">Registrar la consulta y sus antecedentes clínicos</p>
            </div>
            <?php require_once __DIR__ . '/componente/user.php'; ?>
        </header>

        <form method="post" action="index.php?url=NuevaConsulta"
              class="container-fluid shadow-sm p-4 bg-white rounded" data-consulta-form>

                <input type="hidden" name="id_consulta" value="">
                <input type="hidden" name="guardar_consulta" value="1">

            <div class="row g-3 mb-4 pb-3 border-bottom">
                <div class="col-md-6 col-lg-4">
                    <label for="buscarPaciente" class="form-label">Paciente <span class="text-danger">*</span></label>
                    <select class="form-select" id="buscarPaciente" name="id_mascota" required>
                        <option value="">Seleccione una mascota</option>

                            <option value="1">Mascota de ejemplo - Cliente de ejemplo</option>

                    </select>
                </div>
            </div>

            <ul class="nav nav-tabs" id="consultaTabs" role="tablist">

                    <li class="nav-item" role="presentation"><button class="nav-link active text-purple fw-semibold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab" aria-controls="general-pane" aria-selected="true">General</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link text-purple fw-semibold" id="anamnesis-tab" data-bs-toggle="tab" data-bs-target="#anamnesis-pane" type="button" role="tab" aria-controls="anamnesis-pane" aria-selected="false">Anamnesis</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link text-purple fw-semibold" id="examen-tab" data-bs-toggle="tab" data-bs-target="#examen-pane" type="button" role="tab" aria-controls="examen-pane" aria-selected="false">Examen clínico</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link text-purple fw-semibold" id="laboratorio-tab" data-bs-toggle="tab" data-bs-target="#laboratorio-pane" type="button" role="tab" aria-controls="laboratorio-pane" aria-selected="false">Resultados de laboratorio</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link text-purple fw-semibold" id="diagnostico-tab" data-bs-toggle="tab" data-bs-target="#diagnostico-pane" type="button" role="tab" aria-controls="diagnostico-pane" aria-selected="false">Diagnóstico</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link text-purple fw-semibold" id="receta-tab" data-bs-toggle="tab" data-bs-target="#receta-pane" type="button" role="tab" aria-controls="receta-pane" aria-selected="false">Recipe</button></li>

            </ul>

            <div class="tab-content pt-4" id="consultaTabsContent">
                <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
                    <div class="row g-3">
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label" for="fecha">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="" required>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label" for="tipo_ingreso">Tipo de ingreso <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_ingreso" name="tipo_ingreso" required>
                                <option value="">Seleccione</option>
                                <option value="1">Primera vez</option>
                                <option value="2">Sucesivo</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label" for="remitido">¿Fue remitido? <span class="text-danger">*</span></label>
                            <select class="form-select" id="remitido" name="remitido" required>
                                <option value="">Seleccione</option>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pronostico">Pronóstico</label>
                            <input type="text" class="form-control" id="pronostico" name="pronostico" value="" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tratamiento_consulta">Tratamiento en consulta</label>
                            <input type="text" class="form-control" id="tratamiento_consulta" name="tratamiento_consulta" value="" maxlength="80">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="peso_consulta">Peso en tratamiento (kg)</label>
                            <input type="number" class="form-control" id="peso_consulta" name="peso_consulta" value="" min="0" step="0.1">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="comentario_seguimiento">Comentario clínico / seguimiento</label>
                            <textarea class="form-control" id="comentario_seguimiento" name="comentario_seguimiento" maxlength="200" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="anamnesis-pane" role="tabpanel" aria-labelledby="anamnesis-tab">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="motivo_consulta">Motivo de consulta</label>
                            <textarea class="form-control" id="motivo_consulta" name="motivo_consulta" maxlength="200" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="inicio_enfermedad">Inicio de la enfermedad</label>
                            <input type="date" class="form-control" id="inicio_enfermedad" name="inicio_enfermedad" value="">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="examenes_efectuados">Exámenes efectuados</label>
                            <textarea class="form-control" id="examenes_efectuados" name="examenes_efectuados" maxlength="200"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tratamientos_realizados">Tratamientos realizados</label>
                            <textarea class="form-control" id="tratamientos_realizados" name="tratamientos_realizados" maxlength="200"></textarea>
                        </div>

                        <h5 class="text-secondary mt-4 mb-0 border-bottom pb-2">Desparasitación y vacunación</h5>
                        <div class="col-md-3">
                            <label class="form-label" for="ult_desp_interna">Última desparasitación interna</label>
                            <input type="date" class="form-control" id="ult_desp_interna" name="ult_desp_interna">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="ult_desp_int_producto">Producto interno</label>
                            <input type="text" class="form-control" id="ult_desp_int_producto" name="ult_desp_int_producto" maxlength="80">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="ult_desp_externa">Última desparasitación externa</label>
                            <input type="date" class="form-control" id="ult_desp_externa" name="ult_desp_externa">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="ult_desp_ext_producto">Producto externo</label>
                            <input type="text" class="form-control" id="ult_desp_ext_producto" name="ult_desp_ext_producto" maxlength="80">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="ultima_vacunacion">Última vacunación</label>
                            <input type="date" class="form-control" id="ultima_vacunacion" name="ultima_vacunacion">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="ant_ectoparasitos">Antecedente de ectoparásitos</label>
                            <select class="form-select" id="ant_ectoparasitos" name="ant_ectoparasitos">
                                <option value="">Sin información</option><option value="1">Sí</option><option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="act_ectoparasitos">Presenta ectoparásitos actualmente</label>
                            <select class="form-select" id="act_ectoparasitos" name="act_ectoparasitos">
                                <option value="">Sin información</option><option value="1">Sí</option><option value="0">No</option>
                            </select>
                        </div>

                        <h5 class="text-secondary mt-4 mb-0 border-bottom pb-2">Alimentación y hábitos</h5>
                        <div class="col-md-4"><label class="form-label" for="tipo_alimentacion">Tipo de alimentación</label><input type="text" class="form-control" id="tipo_alimentacion" name="tipo_alimentacion" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="frec_alimentacion">Frecuencia de alimentación</label><input type="text" class="form-control" id="frec_alimentacion" name="frec_alimentacion" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="apetito">Apetito</label><input type="text" class="form-control" id="apetito" name="apetito" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="ingesta_agua">Ingesta de agua</label><input type="text" class="form-control" id="ingesta_agua" name="ingesta_agua" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="contacto_animal">Contacto con animales</label><input type="text" class="form-control" id="contacto_animal" name="contacto_animal" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="vomito">Vómito</label><input type="text" class="form-control" id="vomito" name="vomito" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="heces">Heces</label><input type="text" class="form-control" id="heces" name="heces" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="miccion">Micción</label><input type="text" class="form-control" id="miccion" name="miccion" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="fch_ultimo_celo">Fecha del último celo</label><input type="date" class="form-control" id="fch_ultimo_celo" name="fch_ultimo_celo"></div>
                        <div class="col-md-4"><label class="form-label" for="prod_higiene">Producto de higiene</label><input type="text" class="form-control" id="prod_higiene" name="prod_higiene" maxlength="80"></div>
                        <div class="col-md-4"><label class="form-label" for="frec_higiene">Frecuencia de higiene</label><input type="text" class="form-control" id="frec_higiene" name="frec_higiene" maxlength="80"></div>
                        <div class="col-md-6"><label class="form-label" for="int_ambiente">Ambiente interno</label><input type="text" class="form-control" id="int_ambiente" name="int_ambiente" maxlength="100"></div>
                        <div class="col-md-6"><label class="form-label" for="ext_ambiente">Ambiente externo</label><input type="text" class="form-control" id="ext_ambiente" name="ext_ambiente" maxlength="100"></div>

                    </div>
                </div>

                <div class="tab-pane fade" id="examen-pane" role="tabpanel" aria-labelledby="examen-tab">
                    <div class="row g-3">

                        <div class="col-md-3"><label class="form-label" for="peso">Peso (kg)</label><input type="number" step="0.01" min="0" class="form-control" id="peso" name="peso"></div>
                        <div class="col-md-3"><label class="form-label" for="cc">Condición corporal</label><input type="text" class="form-control" id="cc" name="cc"></div>
                        <div class="col-md-3"><label class="form-label" for="temp_celsius">Temperatura (°C)</label><input type="number" step="0.1" class="form-control" id="temp_celsius" name="temp_celsius"></div>
                        <div class="col-md-3"><label class="form-label" for="pulso_yugular">Pulso yugular</label><input type="text" class="form-control" id="pulso_yugular" name="pulso_yugular"></div>
                        <div class="col-md-3"><label class="form-label" for="fr_rpm">FR (rpm)</label><input type="number" class="form-control" id="fr_rpm" name="fr_rpm"></div>
                        <div class="col-md-3"><label class="form-label" for="fc_lpm">FC (lpm)</label><input type="number" class="form-control" id="fc_lpm" name="fc_lpm"></div>
                        <div class="col-md-3"><label class="form-label" for="pulso_ppm">Pulso (ppm)</label><input type="number" class="form-control" id="pulso_ppm" name="pulso_ppm"></div>
                        <div class="col-md-3"><label class="form-label" for="tlc_seg">TLC (seg)</label><input type="number" step="0.1" class="form-control" id="tlc_seg" name="tlc_seg"></div>
                        <div class="col-md-3"><label class="form-label" for="tpc_seg">TPC (seg)</label><input type="number" step="0.1" class="form-control" id="tpc_seg" name="tpc_seg"></div>
                        <div class="col-md-3"><label class="form-label" for="pas">PAS</label><input type="number" class="form-control" id="pas" name="pas"></div>
                        <div class="col-md-3"><label class="form-label" for="pad">PAD</label><input type="number" class="form-control" id="pad" name="pad"></div>
                        <div class="col-md-3"><label class="form-label" for="prcnt_deshidratacion">Deshidratación (%)</label><input type="number" step="0.1" class="form-control" id="prcnt_deshidratacion" name="prcnt_deshidratacion"></div>
                        <div class="col-md-6"><label class="form-label" for="gangliios_palpables">Ganglios palpables</label><input type="text" class="form-control" id="gangliios_palpables" name="gangliios_palpables"></div>
                        <div class="col-md-6"><label class="form-label" for="mucosas_visibles">Mucosas visibles</label><input type="text" class="form-control" id="mucosas_visibles" name="mucosas_visibles"></div>
                        <div class="col-md-6"><label class="form-label" for="ectoparasitos">Ectoparásitos</label><input type="text" class="form-control" id="ectoparasitos" name="ectoparasitos"></div>
                        <div class="col-md-6"><label class="form-label" for="actitud">Actitud</label><input type="text" class="form-control" id="actitud" name="actitud"></div>

                        <div class="col-12">
                            <label class="form-label" for="hallazgos">Hallazgos</label>
                            <textarea class="form-control" id="hallazgos" name="hallazgos" maxlength="400" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="laboratorio-pane" role="tabpanel" aria-labelledby="laboratorio-tab">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="hematologia_completa">Hematología</label>
                            <textarea class="form-control" id="hematologia_completa" name="hematologia_completa" maxlength="300"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="coprologia">Coprología</label>
                            <textarea class="form-control" id="coprologia" name="coprologia" maxlength="300"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quimica_sanguinea">Química sanguínea</label>
                            <textarea class="form-control" id="quimica_sanguinea" name="quimica_sanguinea" maxlength="300"></textarea>
                        </div>
                        <h5 class="text-secondary mt-3 mb-0 border-bottom pb-2">Uroanálisis</h5>

                        <div class="col-md-3"><label class="form-label" for="uro_sangre">Sangre</label><input type="text" class="form-control" id="uro_sangre" name="uro_sangre" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_urob">Urobilinógeno</label><input type="text" class="form-control" id="uro_urob" name="uro_urob" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_bli">Bilirrubina</label><input type="text" class="form-control" id="uro_bli" name="uro_bli" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_prot">Proteínas</label><input type="text" class="form-control" id="uro_prot" name="uro_prot" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_nitritos">Nitritos</label><input type="text" class="form-control" id="uro_nitritos" name="uro_nitritos" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_cetona">Cetonas</label><input type="text" class="form-control" id="uro_cetona" name="uro_cetona" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_glucosa">Glucosa</label><input type="text" class="form-control" id="uro_glucosa" name="uro_glucosa" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_ph">pH</label><input type="text" class="form-control" id="uro_ph" name="uro_ph" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_leu">Leucocitos</label><input type="text" class="form-control" id="uro_leu" name="uro_leu" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_densidad">Densidad</label><input type="text" class="form-control" id="uro_densidad" name="uro_densidad" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_microorganismos">Microorganismos</label><input type="text" class="form-control" id="uro_microorganismos" name="uro_microorganismos" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_celulas">Células</label><input type="text" class="form-control" id="uro_celulas" name="uro_celulas" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_cilindros">Cilindros</label><input type="text" class="form-control" id="uro_cilindros" name="uro_cilindros" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label" for="uro_cristales">Cristales</label><input type="text" class="form-control" id="uro_cristales" name="uro_cristales" maxlength="50"></div>
                        <div class="col-md-4"><label class="form-label" for="descarte">Descarte</label><input type="text" class="form-control" id="descarte" name="descarte" maxlength="50"></div>
                        <div class="col-md-4"><label class="form-label" for="piel_otros">Piel u otros</label><input type="text" class="form-control" id="piel_otros" name="piel_otros" maxlength="50"></div>
                        <div class="col-md-4"><label class="form-label" for="snap">SNAP</label><input type="text" class="form-control" id="snap" name="snap" maxlength="50"></div>

                        <div class="col-12">
                            <label class="form-label" for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" maxlength="150"></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="diagnostico-pane" role="tabpanel" aria-labelledby="diagnostico-tab">
                    <p class="text-muted">Agregue uno o más diagnósticos; puede dejar esta sección vacía.</p>
                    <div id="diagnosticos-container">

                        <div class="row g-3 align-items-end mb-3 diagnostico-row">
                            <input type="hidden" name="id_diagnostico[]" value="">
                            <div class="col-md-5">
                                <label class="form-label">Patología</label>
                                <select class="form-select tom-select" name="id_patologia[]">
                                    <option value="">Seleccione una patología</option>

                                        <option value=""></option>

                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Tipo de diagnóstico</label>
                                <input type="text" class="form-control" name="tipo_diagnostico[]" value="" maxlength="80">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 quitar-fila" aria-label="Quitar diagnóstico">Quitar</button>
                            </div>
                        </div>

                    </div>
                    <button type="button" class="btn btn-outline-success" data-agregar="diagnostico">
                        <i class="fa-solid fa-plus"></i> Agregar diagnóstico
                    </button>
                </div>

                <div class="tab-pane fade" id="receta-pane" role="tabpanel" aria-labelledby="receta-tab">
                    <p class="text-muted">Agregue los medicamentos recetados; esta sección es opcional.</p>
                    <div id="recetas-container">

                        <div class="row g-3 align-items-end mb-3 receta-row">
                            <input type="hidden" name="id_recipe[]" value="">
                            <div class="col-md-3">
                                <label class="form-label">Medicamento</label>
                                <select class="form-select tom-select" name="id_medicamento[]">
                                    <option value="">Seleccione un medicamento</option>

                                        <option value=""></option>

                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Dosis</label>
                                <input type="text" class="form-control" name="dosis[]" value="" maxlength="150">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Frecuencia</label>
                                <input type="text" class="form-control" name="frecuencia[]" value="" maxlength="150">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duración</label>
                                <input type="text" class="form-control" name="duracion[]" value="" maxlength="150">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 quitar-fila" aria-label="Quitar medicamento">Quitar</button>
                            </div>
                        </div>

                    </div>
                    <button type="button" class="btn btn-outline-success" data-agregar="receta">
                        <i class="fa-solid fa-plus"></i> Agregar medicamento
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <a href="index.php?url=Consulta" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-success px-4 fw-bold" name="guardar_consulta" value="1"
                        >Guardar consulta</button>
            </div>
        </form>
    </main>
</div>

<template id="diagnostico-template">
    <div class="row g-3 align-items-end mb-3 diagnostico-row">
        <input type="hidden" name="id_diagnostico[]" value="0">
        <div class="col-md-5">
            <label class="form-label">Patología</label>
            <select class="form-select tom-select" name="id_patologia[]">
                <option value="">Seleccione una patología</option>

                    <option value=""></option>

            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label">Tipo de diagnóstico</label>
            <input type="text" class="form-control" name="tipo_diagnostico[]" maxlength="80">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger w-100 quitar-fila" aria-label="Quitar diagnóstico">Quitar</button>
        </div>
    </div>
</template>
<template id="receta-template">
    <div class="row g-3 align-items-end mb-3 receta-row">
        <input type="hidden" name="id_recipe[]" value="0">
        <div class="col-md-3">
            <label class="form-label">Medicamento</label>
            <select class="form-select tom-select" name="id_medicamento[]">
                <option value="">Seleccione un medicamento</option>

                    <option value=""></option>

            </select>
        </div>
        <div class="col-md-2"><label class="form-label">Dosis</label><input type="text" class="form-control" name="dosis[]" maxlength="150"></div>
        <div class="col-md-2"><label class="form-label">Frecuencia</label><input type="text" class="form-control" name="frecuencia[]" maxlength="150"></div>
        <div class="col-md-3"><label class="form-label">Duración</label><input type="text" class="form-control" name="duracion[]" maxlength="150"></div>
        <div class="col-md-2"><button type="button" class="btn btn-outline-danger w-100 quitar-fila" aria-label="Quitar medicamento">Quitar</button></div>
    </div>
</template>

<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/tom-select.complete.js"></script>
<script src="public/js/consulta.js"></script>
</body>
</html>
