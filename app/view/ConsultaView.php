<?php
$escapar = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$etiquetas = [
    'anamnesis' => [
        'motivo_consulta' => 'Motivo de consulta',
        'inicio_enfermedad' => 'Inicio de la enfermedad',
        'examenes_efectuados' => 'Exámenes efectuados',
        'tratamientos_realizados' => 'Tratamientos realizados',
        'ult_desp_interna' => 'Última desparasitación interna',
        'ult_desp_int_producto' => 'Producto interno',
        'ult_desp_externa' => 'Última desparasitación externa',
        'ult_desp_ext_producto' => 'Producto externo',
        'ultima_vacunacion' => 'Última vacunación',
        'tipo_alimentacion' => 'Tipo de alimentación',
        'frec_alimentacion' => 'Frecuencia de alimentación',
        'apetito' => 'Apetito',
        'ingesta_agua' => 'Ingesta de agua',
        'contacto_animal' => 'Contacto con animales',
        'vomito' => 'Vómito',
        'heces' => 'Heces',
        'miccion' => 'Micción',
        'fch_ultimo_celo' => 'Fecha del último celo',
        'prod_higiene' => 'Producto de higiene',
        'frec_higiene' => 'Frecuencia de higiene',
        'int_ambiente' => 'Ambiente interno',
        'ext_ambiente' => 'Ambiente externo',
        'act_ectoparasitos' => 'Ectoparásitos actuales',
        'ant_ectoparasitos' => 'Antecedente de ectoparásitos'
    ],
    'examen' => [
        'peso' => 'Peso (kg)', 'cc' => 'Condición corporal', 'temp_celsius' => 'Temperatura (°C)',
        'pulso_yugular' => 'Pulso yugular', 'fr_rpm' => 'FR (rpm)', 'fc_lpm' => 'FC (lpm)',
        'pulso_ppm' => 'Pulso (ppm)', 'tlc_seg' => 'TLC (seg)', 'tpc_seg' => 'TPC (seg)',
        'pas' => 'PAS', 'pad' => 'PAD', 'prcnt_deshidratacion' => 'Deshidratación (%)',
        'gangliios_palpables' => 'Ganglios palpables', 'mucosas_visibles' => 'Mucosas visibles',
        'ectoparasitos' => 'Ectoparásitos', 'actitud' => 'Actitud', 'hallazgos' => 'Hallazgos'
    ],
    'laboratorio' => [
        'hematologia_completa' => 'Hematología', 'coprologia' => 'Coprología',
        'quimica_sanguinea' => 'Química sanguínea', 'uro_sangre' => 'Sangre',
        'uro_urob' => 'Urobilinógeno', 'uro_bli' => 'Bilirrubina', 'uro_prot' => 'Proteínas',
        'uro_nitritos' => 'Nitritos', 'uro_cetona' => 'Cetonas', 'uro_glucosa' => 'Glucosa',
        'uro_ph' => 'pH', 'uro_leu' => 'Leucocitos', 'uro_densidad' => 'Densidad',
        'uro_microorganismos' => 'Microorganismos', 'uro_celulas' => 'Células',
        'uro_cilindros' => 'Cilindros', 'uro_cristales' => 'Cristales', 'descarte' => 'Descarte',
        'piel_otros' => 'Piel u otros', 'snap' => 'SNAP', 'observaciones' => 'Observaciones'
    ]
];
$mostrarCampos = static function (array $datos, array $nombres) use ($escapar): string {
    $contenido = '';
    foreach ($nombres as $columna => $etiqueta) {
        $valor = $datos[$columna] ?? null;
        if ($valor === null || $valor === '') {
            continue;
        }
        if (in_array($columna, ['act_ectoparasitos', 'ant_ectoparasitos'], true)) {
            $valor = (int) $valor === 1 ? 'Sí' : 'No';
        }
        $contenido .= '<div class="col-md-6 mb-3"><dt>' . $escapar($etiqueta) . '</dt><dd class="mb-0 text-break">'
            . nl2br($escapar($valor)) . '</dd></div>';
    }
    return $contenido !== '' ? '<dl class="row mb-0">' . $contenido . '</dl>'
        : '<p class="text-muted mb-0">Sin información registrada.</p>';
};
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Consultas</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>
<div class="d-flex">
    <?php require_once __DIR__ . '/componente/menu.php'; ?>
    <main class="main-content w-100 p-4" data-consulta-error="<?= $escapar($consultaError) ?>">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-purple mb-0">Consultas</h2>
                <p class="text-muted">Historial de consultas veterinarias</p>
            </div>
            <?php require_once __DIR__ . '/componente/user.php'; ?>
        </header>

        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?url=NuevaConsulta" class="btn btn-success"><i class="bi bi-plus"></i> Agregar consulta</a>
        </div>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th class="table-purple">Id</th>
                    <th class="table-purple">Mascota</th>
                    <th class="table-purple">Responsable</th>
                    <th class="table-purple">Fecha</th>
                    <th class="table-purple">Tipo de ingreso</th>
                    <th class="table-purple">Remitido</th>
                    <th class="table-purple">Pronóstico</th>
                    <th class="table-purple">Tratamiento</th>
                    <th class="table-purple">Peso</th>
                    <th class="table-purple">Seguimiento</th>
                    <th class="table-purple">Detalle</th>
                    <th class="table-purple">Acciones</th>
                </tr>
                </thead>
                <tbody id="tabla-consultas" data-url="index.php?url=Consulta">
                <?php if (!$consultas): ?>
                    <tr><td colspan="12" class="text-center text-muted py-4">Todavía no hay consultas registradas.</td></tr>
                <?php endif; ?>
                <?php foreach ($consultas as $consulta): ?>
                    <?php $modalId = 'consulta-detalle-' . (int) $consulta['id_consulta']; ?>
                    <tr>
                        <td><?= (int) $consulta['id_consulta'] ?></td>
                        <td><?= $escapar($consulta['nombre_mascota']) ?></td>
                        <td><?= $escapar($consulta['nombre_cliente']) ?></td>
                        <td><?= $escapar($consulta['fecha']) ?></td>
                        <td><?= (int) $consulta['tipo_ingreso'] === 1 ? 'Primera vez' : 'Sucesivo' ?></td>
                        <td><?= (int) $consulta['remitido'] === 1 ? 'Sí' : 'No' ?></td>
                        <td><?= $escapar($consulta['pronostico'] ?: '—') ?></td>
                        <td><?= $escapar($consulta['tratamiento_consulta'] ?: '—') ?></td>
                        <td><?= $escapar($consulta['peso_consulta'] ?: '—') ?></td>
                        <td><?= $escapar($consulta['comentario'] ?: '—') ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#<?= $modalId ?>">Ver detalle</button>
                        </td>
                        <td class="text-nowrap">
                            <a href="index.php?url=NuevaConsulta&amp;id_consulta=<?= (int) $consulta['id_consulta'] ?>"
                               class="btn btn-sm btn-success">Actualizar</a>
                            <form method="post" action="index.php?url=Consulta" class="d-inline" data-eliminar-consulta>
                                <input type="hidden" name="id_consulta" value="<?= (int) $consulta['id_consulta'] ?>">
                                <input type="hidden" name="eliminar_consulta" value="1">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php foreach ($consultas as $consulta): ?>
    <?php $modalId = 'consulta-detalle-' . (int) $consulta['id_consulta']; ?>
    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>-label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="<?= $modalId ?>-label">Consulta #<?= (int) $consulta['id_consulta'] ?></h5>
                        <div class="small text-white-50">
                            <?= $escapar($consulta['nombre_mascota']) ?> · <?= $escapar($consulta['fecha']) ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                        $tabsDetalle = [
                            ['general', 'General'],
                            ['anamnesis', 'Anamnesis'],
                            ['examen', 'Examen clínico'],
                            ['laboratorio', 'Laboratorio'],
                            ['diagnosticos', 'Diagnósticos'],
                            ['recetas', 'Recipe']
                        ];
                        foreach ($tabsDetalle as $indice => [$id, $titulo]):
                            $tabId = $modalId . '-' . $id;
                        ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?= $indice === 0 ? 'active' : '' ?>" id="<?= $tabId ?>-tab"
                                        data-bs-toggle="tab" data-bs-target="#<?= $tabId ?>-pane" type="button"
                                        role="tab" aria-controls="<?= $tabId ?>-pane"
                                        aria-selected="<?= $indice === 0 ? 'true' : 'false' ?>"><?= $titulo ?></button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active" id="<?= $modalId ?>-general-pane" role="tabpanel">
                            <dl class="row mb-0">
                                <div class="col-md-6 mb-3"><dt>Paciente / responsable</dt><dd><?= $escapar($consulta['nombre_mascota']) ?> / <?= $escapar($consulta['nombre_cliente']) ?></dd></div>
                                <div class="col-md-6 mb-3"><dt>Fecha y tipo</dt><dd><?= $escapar($consulta['fecha']) ?> · <?= (int) $consulta['tipo_ingreso'] === 1 ? 'Primera vez' : 'Sucesivo' ?></dd></div>
                                <div class="col-md-6 mb-3"><dt>Remitido</dt><dd><?= (int) $consulta['remitido'] === 1 ? 'Sí' : 'No' ?></dd></div>
                                <div class="col-md-6 mb-3"><dt>Pronóstico</dt><dd><?= nl2br($escapar($consulta['pronostico'] ?: '—')) ?></dd></div>
                                <div class="col-md-6 mb-3"><dt>Tratamiento en consulta</dt><dd><?= nl2br($escapar($consulta['tratamiento_consulta'] ?: '—')) ?></dd></div>
                                <div class="col-md-6 mb-3"><dt>Peso en tratamiento</dt><dd><?= $escapar($consulta['peso_consulta'] ?: '—') ?></dd></div>
                                <div class="col-12 mb-3"><dt>Comentario / seguimiento</dt><dd><?= nl2br($escapar($consulta['comentario'] ?: '—')) ?></dd></div>
                            </dl>
                        </div>
                        <?php foreach (['anamnesis', 'examen', 'laboratorio'] as $seccion): ?>
                            <div class="tab-pane fade" id="<?= $modalId . '-' . $seccion ?>-pane" role="tabpanel">
                                <?= $mostrarCampos($consulta['detalle'][$seccion], $etiquetas[$seccion]) ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="tab-pane fade" id="<?= $modalId ?>-diagnosticos-pane" role="tabpanel">
                            <?php if (!$consulta['detalle']['diagnosticos']): ?>
                                <p class="text-muted mb-0">Sin diagnósticos registrados.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($consulta['detalle']['diagnosticos'] as $diagnostico): ?>
                                        <li class="list-group-item">
                                            <strong><?= $escapar($diagnostico['patologia']) ?></strong>
                                            <span class="text-muted"> — <?= $escapar($diagnostico['tipo_diagnostico']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="<?= $modalId ?>-recetas-pane" role="tabpanel">
                            <?php if (!$consulta['detalle']['recetas']): ?>
                                <p class="text-muted mb-0">Sin medicamentos recetados.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead><tr><th>Medicamento</th><th>Dosis</th><th>Frecuencia</th><th>Duración</th></tr></thead>
                                        <tbody>
                                        <?php foreach ($consulta['detalle']['recetas'] as $receta): ?>
                                            <tr>
                                                <td><?= $escapar($receta['nombre_medicamento']) ?></td>
                                                <td><?= $escapar($receta['dosis']) ?></td>
                                                <td><?= $escapar($receta['frecuencia']) ?></td>
                                                <td><?= $escapar($receta['duracion']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/consulta.js"></script>
</body>
</html>
