<?php
$escapar = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$valorCampo = static function (string $campo, string $predeterminado = '') use ($escapar, $consultaValores): string {
    $valor = $_POST[$campo] ?? $consultaValores[$campo] ?? $predeterminado;
    return is_scalar($valor) ? $escapar($valor) : '';
};
$seleccionado = static function (string $campo, string $valor) use ($consultaValores): string {
    $seleccion = $_POST[$campo] ?? $consultaValores[$campo] ?? '';
    return is_scalar($seleccion) && (string) $seleccion === $valor ? ' selected' : '';
};
$esEdicion = $idConsultaEdicion > 0;
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Nueva consulta</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>
<div class="d-flex">
    <?php require_once __DIR__ . '/componente/menu.php'; ?>
    <main class="main-content w-100 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-purple mb-0"><?= $esEdicion ? 'Actualizar consulta' : 'Nueva consulta' ?></h2>
                <p class="text-muted"><?= $esEdicion ? 'Editar los datos clínicos registrados' : 'Registrar la consulta y sus antecedentes clínicos' ?></p>
            </div>
            <?php require_once __DIR__ . '/componente/user.php'; ?>
        </header>

        <?php if ($consultaError !== ''): ?>
            <div class="alert alert-danger" role="alert"><?= $escapar($consultaError) ?></div>
        <?php endif; ?>

        <?php if (!$mascotas): ?>
            <div class="alert alert-warning" role="alert">No hay mascotas activas disponibles para registrar una consulta.</div>
        <?php endif; ?>

        <form method="post" action="index.php?url=NuevaConsulta<?= $esEdicion ? '&id_consulta=' . $idConsultaEdicion : '' ?>"
              class="container-fluid shadow-sm p-4 bg-white rounded" data-consulta-form>
            <?php if ($esEdicion): ?>
                <input type="hidden" name="id_consulta" value="<?= $idConsultaEdicion ?>">
            <?php endif; ?>
            <div class="row g-3 mb-4 pb-3 border-bottom">
                <div class="col-md-6 col-lg-4">
                    <label for="buscarPaciente" class="form-label">Paciente <span class="text-danger">*</span></label>
                    <select class="form-select" id="buscarPaciente" name="id_mascota" required>
                        <option value="">Seleccione una mascota</option>
                        <?php foreach ($mascotas as $mascota): ?>
                            <option value="<?= (int) $mascota['id_mascota'] ?>"<?= $seleccionado('id_mascota', (string) $mascota['id_mascota']) ?>>
                                <?= $escapar($mascota['nombre']) ?> - <?= $escapar($mascota['nombre_cliente']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <ul class="nav nav-tabs" id="consultaTabs" role="tablist">
                <?php
                $pestanas = [
                    ['general', 'General'],
                    ['anamnesis', 'Anamnesis'],
                    ['examen', 'Examen clínico'],
                    ['laboratorio', 'Resultados de laboratorio'],
                    ['diagnostico', 'Diagnóstico'],
                    ['receta', 'Recipe']
                ];
                foreach ($pestanas as $indice => [$id, $titulo]):
                ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $indice === 0 ? 'active' : '' ?> text-purple fw-semibold"
                                id="<?= $id ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $id ?>-pane"
                                type="button" role="tab" aria-controls="<?= $id ?>-pane"
                                aria-selected="<?= $indice === 0 ? 'true' : 'false' ?>"><?= $titulo ?></button>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content pt-4" id="consultaTabsContent">
                <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
                    <div class="row g-3">
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label" for="fecha">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $valorCampo('fecha', date('Y-m-d')) ?>" required>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label" for="tipo_ingreso">Tipo de ingreso <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipo_ingreso" name="tipo_ingreso" required>
                                <option value="">Seleccione</option>
                                <option value="1"<?= $seleccionado('tipo_ingreso', '1') ?>>Primera vez</option>
                                <option value="2"<?= $seleccionado('tipo_ingreso', '2') ?>>Sucesivo</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label" for="remitido">¿Fue remitido? <span class="text-danger">*</span></label>
                            <select class="form-select" id="remitido" name="remitido" required>
                                <option value="">Seleccione</option>
                                <option value="1"<?= $seleccionado('remitido', '1') ?>>Sí</option>
                                <option value="0"<?= $seleccionado('remitido', '0') ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pronostico">Pronóstico</label>
                            <input type="text" class="form-control" id="pronostico" name="pronostico" value="<?= $valorCampo('pronostico') ?>" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tratamiento_consulta">Tratamiento en consulta</label>
                            <input type="text" class="form-control" id="tratamiento_consulta" name="tratamiento_consulta" value="<?= $valorCampo('tratamiento_consulta') ?>" maxlength="80">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="peso_consulta">Peso en tratamiento (kg)</label>
                            <input type="number" class="form-control" id="peso_consulta" name="peso_consulta" value="<?= $valorCampo('peso_consulta') ?>" min="0" step="0.1">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="comentario_seguimiento">Comentario clínico / seguimiento</label>
                            <textarea class="form-control" id="comentario_seguimiento" name="comentario_seguimiento" maxlength="200" rows="3"><?= $valorCampo('comentario_seguimiento') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="anamnesis-pane" role="tabpanel" aria-labelledby="anamnesis-tab">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="motivo_consulta">Motivo de consulta</label>
                            <textarea class="form-control" id="motivo_consulta" name="motivo_consulta" maxlength="200" rows="2"><?= $valorCampo('motivo_consulta') ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="inicio_enfermedad">Inicio de la enfermedad</label>
                            <input type="date" class="form-control" id="inicio_enfermedad" name="inicio_enfermedad" value="<?= $valorCampo('inicio_enfermedad') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="examenes_efectuados">Exámenes efectuados</label>
                            <textarea class="form-control" id="examenes_efectuados" name="examenes_efectuados" maxlength="200"><?= $valorCampo('examenes_efectuados') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tratamientos_realizados">Tratamientos realizados</label>
                            <textarea class="form-control" id="tratamientos_realizados" name="tratamientos_realizados" maxlength="200"><?= $valorCampo('tratamientos_realizados') ?></textarea>
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
                                <option value="">Sin información</option><option value="1"<?= $seleccionado('ant_ectoparasitos', '1') ?>>Sí</option><option value="0"<?= $seleccionado('ant_ectoparasitos', '0') ?>>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="act_ectoparasitos">Presenta ectoparásitos actualmente</label>
                            <select class="form-select" id="act_ectoparasitos" name="act_ectoparasitos">
                                <option value="">Sin información</option><option value="1"<?= $seleccionado('act_ectoparasitos', '1') ?>>Sí</option><option value="0"<?= $seleccionado('act_ectoparasitos', '0') ?>>No</option>
                            </select>
                        </div>

                        <h5 class="text-secondary mt-4 mb-0 border-bottom pb-2">Alimentación y hábitos</h5>
                        <?php
                        $camposAnamnesis = [
                            ['tipo_alimentacion', 'Tipo de alimentación', 100],
                            ['frec_alimentacion', 'Frecuencia de alimentación', 100],
                            ['apetito', 'Apetito', 100],
                            ['ingesta_agua', 'Ingesta de agua', 100],
                            ['vomito', 'Vómito', 100],
                            ['heces', 'Heces', 100],
                            ['miccion', 'Micción', 100],
                            ['contacto_animal', 'Contacto con animales', 100],
                            ['fch_ultimo_celo', 'Fecha del último celo', 'date'],
                            ['prod_higiene', 'Producto de higiene', 80],
                            ['frec_higiene', 'Frecuencia de higiene', 100],
                            ['int_ambiente', 'Ambiente interno', 100],
                            ['ext_ambiente', 'Ambiente externo', 100]
                        ];
                        foreach ($camposAnamnesis as [$campo, $etiqueta, $limite]):
                        ?>
                            <div class="col-md-4">
                                <label class="form-label" for="<?= $campo ?>"><?= $etiqueta ?></label>
                                <input type="<?= $limite === 'date' ? 'date' : 'text' ?>" class="form-control"
                                       id="<?= $campo ?>" name="<?= $campo ?>" value="<?= $valorCampo($campo) ?>"
                                       <?= $limite === 'date' ? '' : 'maxlength="' . (int) $limite . '"' ?>>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="examen-pane" role="tabpanel" aria-labelledby="examen-tab">
                    <div class="row g-3">
                        <?php
                        $camposExamen = [
                            ['peso', 'Peso (kg)', 'number', '0.1'],
                            ['cc', 'Condición corporal (1-9)', 'number', '1'],
                            ['temp_celsius', 'Temperatura (°C)', 'number', '0.1'],
                            ['pulso_yugular', 'Pulso yugular', 'text', null],
                            ['fr_rpm', 'FR (rpm)', 'number', '1'],
                            ['fc_lpm', 'FC (lpm)', 'number', '1'],
                            ['pulso_ppm', 'Pulso (ppm)', 'number', '1'],
                            ['tlc_seg', 'TLC (seg)', 'number', '1'],
                            ['tpc_seg', 'TPC (seg)', 'number', '1'],
                            ['pas', 'PAS', 'text', null],
                            ['pad', 'PAD', 'text', null],
                            ['prcnt_deshidratacion', 'Deshidratación (%)', 'number', '0.1'],
                            ['gangliios_palpables', 'Ganglios palpables', 'text', null],
                            ['mucosas_visibles', 'Mucosas visibles', 'text', null],
                            ['ectoparasitos', 'Ectoparásitos', 'text', null],
                            ['actitud', 'Actitud', 'text', null]
                        ];
                        foreach ($camposExamen as [$campo, $etiqueta, $tipo, $paso]):
                        ?>
                            <div class="col-md-3">
                                <label class="form-label" for="<?= $campo ?>"><?= $etiqueta ?></label>
                                <input type="<?= $tipo ?>" class="form-control" id="<?= $campo ?>" name="<?= $campo ?>"
                                       value="<?= $valorCampo($campo) ?>"
                                       <?= $paso !== null ? 'step="' . $paso . '"' : '' ?>>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12">
                            <label class="form-label" for="hallazgos">Hallazgos</label>
                            <textarea class="form-control" id="hallazgos" name="hallazgos" maxlength="400" rows="3"><?= $valorCampo('hallazgos') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="laboratorio-pane" role="tabpanel" aria-labelledby="laboratorio-tab">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="hematologia_completa">Hematología</label>
                            <textarea class="form-control" id="hematologia_completa" name="hematologia_completa" maxlength="300"><?= $valorCampo('hematologia_completa') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="coprologia">Coprología</label>
                            <textarea class="form-control" id="coprologia" name="coprologia" maxlength="300"><?= $valorCampo('coprologia') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quimica_sanguinea">Química sanguínea</label>
                            <textarea class="form-control" id="quimica_sanguinea" name="quimica_sanguinea" maxlength="300"><?= $valorCampo('quimica_sanguinea') ?></textarea>
                        </div>
                        <h5 class="text-secondary mt-3 mb-0 border-bottom pb-2">Uroanálisis</h5>
                        <?php
                        $camposLaboratorio = [
                            ['uro_sangre', 'Sangre'], ['uro_urob', 'Urobilinógeno'], ['uro_bli', 'Bilirrubina'],
                            ['uro_prot', 'Proteínas'], ['uro_nitritos', 'Nitritos'], ['uro_cetona', 'Cetonas'],
                            ['uro_glucosa', 'Glucosa'], ['uro_ph', 'pH'], ['uro_leu', 'Leucocitos'],
                            ['uro_densidad', 'Densidad'], ['uro_microorganismos', 'Microorganismos'],
                            ['uro_celulas', 'Células'], ['uro_cilindros', 'Cilindros'], ['uro_cristales', 'Cristales'],
                            ['descarte', 'Descarte'], ['piel_otros', 'Piel u otros'], ['snap', 'SNAP']
                        ];
                        foreach ($camposLaboratorio as [$campo, $etiqueta]):
                        ?>
                            <div class="col-md-3">
                                <label class="form-label" for="<?= $campo ?>"><?= $etiqueta ?></label>
                                <input type="text" class="form-control" id="<?= $campo ?>" name="<?= $campo ?>"
                                       value="<?= $valorCampo($campo) ?>" maxlength="50">
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12">
                            <label class="form-label" for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" maxlength="150"><?= $valorCampo('observaciones') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="diagnostico-pane" role="tabpanel" aria-labelledby="diagnostico-tab">
                    <p class="text-muted">Agregue uno o más diagnósticos; puede dejar esta sección vacía.</p>
                    <div id="diagnosticos-container">
                        <?php foreach ($diagnosticosForm ?: [['id_patologia' => '', 'tipo_diagnostico' => '']] as $diagnosticoForm): ?>
                        <div class="row g-3 align-items-end mb-3 diagnostico-row">
                            <div class="col-md-5">
                                <label class="form-label">Patología</label>
                                <select class="form-select" name="id_patologia[]">
                                    <option value="">Seleccione una patología</option>
                                    <?php foreach ($patologias as $patologia): ?>
                                        <option value="<?= (int) $patologia['id_patologia'] ?>"<?= (string) ($diagnosticoForm['id_patologia'] ?? '') === (string) $patologia['id_patologia'] ? ' selected' : '' ?>><?= $escapar($patologia['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Tipo de diagnóstico</label>
                                <input type="text" class="form-control" name="tipo_diagnostico[]" value="<?= $escapar($diagnosticoForm['tipo_diagnostico'] ?? '') ?>" maxlength="80">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 quitar-fila" aria-label="Quitar diagnóstico">Quitar</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-success" data-agregar="diagnostico">
                        <i class="fa-solid fa-plus"></i> Agregar diagnóstico
                    </button>
                </div>

                <div class="tab-pane fade" id="receta-pane" role="tabpanel" aria-labelledby="receta-tab">
                    <p class="text-muted">Agregue los medicamentos recetados; esta sección es opcional.</p>
                    <div id="recetas-container">
                        <?php foreach ($recetasForm ?: [['id_medicamento' => '', 'dosis' => '', 'frecuencia' => '', 'duracion' => '']] as $recetaForm): ?>
                        <div class="row g-3 align-items-end mb-3 receta-row">
                            <div class="col-md-3">
                                <label class="form-label">Medicamento</label>
                                <select class="form-select" name="id_medicamento[]">
                                    <option value="">Seleccione un medicamento</option>
                                    <?php foreach ($medicamentos as $medicamento): ?>
                                        <option value="<?= (int) $medicamento['id_medicamento'] ?>"<?= (string) ($recetaForm['id_medicamento'] ?? '') === (string) $medicamento['id_medicamento'] ? ' selected' : '' ?>><?= $escapar($medicamento['nombre_medicamento']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Dosis</label>
                                <input type="text" class="form-control" name="dosis[]" value="<?= $escapar($recetaForm['dosis'] ?? '') ?>" maxlength="150">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Frecuencia</label>
                                <input type="text" class="form-control" name="frecuencia[]" value="<?= $escapar($recetaForm['frecuencia'] ?? '') ?>" maxlength="150">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Duración</label>
                                <input type="text" class="form-control" name="duracion[]" value="<?= $escapar($recetaForm['duracion'] ?? '') ?>" maxlength="150">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 quitar-fila" aria-label="Quitar medicamento">Quitar</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-success" data-agregar="receta">
                        <i class="fa-solid fa-plus"></i> Agregar medicamento
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <a href="index.php?url=Consulta" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-success px-4 fw-bold" name="guardar_consulta" value="1"
                        <?= !$mascotas ? 'disabled' : '' ?>><?= $esEdicion ? 'Actualizar consulta' : 'Guardar consulta' ?></button>
            </div>
        </form>
    </main>
</div>

<template id="diagnostico-template">
    <div class="row g-3 align-items-end mb-3 diagnostico-row">
        <div class="col-md-5">
            <label class="form-label">Patología</label>
            <select class="form-select" name="id_patologia[]">
                <option value="">Seleccione una patología</option>
                <?php foreach ($patologias as $patologia): ?>
                    <option value="<?= (int) $patologia['id_patologia'] ?>"><?= $escapar($patologia['nombre']) ?></option>
                <?php endforeach; ?>
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
        <div class="col-md-3">
            <label class="form-label">Medicamento</label>
            <select class="form-select" name="id_medicamento[]">
                <option value="">Seleccione un medicamento</option>
                <?php foreach ($medicamentos as $medicamento): ?>
                    <option value="<?= (int) $medicamento['id_medicamento'] ?>"><?= $escapar($medicamento['nombre_medicamento']) ?></option>
                <?php endforeach; ?>
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
<script src="public/js/consulta.js"></script>
</body>
</html>
