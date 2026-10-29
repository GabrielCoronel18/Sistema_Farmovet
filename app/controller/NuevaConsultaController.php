<?php
use Gabriel\SistemaFarmovet\model\ConsultaModel;
use Gabriel\SistemaFarmovet\model\AnamnesisModel;
use Gabriel\SistemaFarmovet\model\ExamenClinicoModel;
use Gabriel\SistemaFarmovet\model\ResultadosLaboratorioModel;
use Gabriel\SistemaFarmovet\model\DiagnosticoModel;
use Gabriel\SistemaFarmovet\model\RecipeModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$consultaModel = new ConsultaModel();
$idConsulta = (int) ($_GET['id_consulta'] ?? $_POST['id_consulta'] ?? 0);

if (isset($_POST['obtenerCatalogos'])) {
    echo json_encode([
        'status' => 'success',
        'mascotas' => $consultaModel->obtenerMascotasActivas(),
        'patologias' => $consultaModel->obtenerPatologiasActivas(),
        'medicamentos' => $consultaModel->obtenerMedicamentosActivos()
    ]);
    exit;
}

if (isset($_POST['obtenerConsulta']) && $idConsulta > 0) {
    $consulta = $consultaModel->obtenerConsultaPorId($idConsulta);
    if ($consulta) {
        $consulta['anamnesis'] = (new AnamnesisModel())->obtenerAnamnesis($idConsulta);
        $consulta['examen'] = (new ExamenClinicoModel())->obtenerExamen($idConsulta);
        $consulta['laboratorio'] = (new ResultadosLaboratorioModel())->obtenerResultado($idConsulta);
        $consulta['diagnosticos'] = (new DiagnosticoModel())->obtenerDiagnosticos($idConsulta);
        $consulta['recetas'] = (new RecipeModel())->obtenerRecipes($idConsulta);
    }
    echo json_encode(['status' => $consulta ? 'success' : 'error', 'resultado' => $consulta ?: []]);
    exit;
}

if (isset($_POST['guardar_consulta']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'cedula_usuario' => (string) ($_SESSION['usuario']['cedula_usuario'] ?? ''),
        'id_mascota' => (int) ($_POST['id_mascota'] ?? 0),
        'fecha' => (string) ($_POST['fecha'] ?? ''),
        'tipo_ingreso' => (int) ($_POST['tipo_ingreso'] ?? 0),
        'remitido' => (int) ($_POST['remitido'] ?? 0),
        'pronostico' => trim((string) ($_POST['pronostico'] ?? '')),
        'tratamiento_consulta' => trim((string) ($_POST['tratamiento_consulta'] ?? '')),
        'peso_consulta' => trim((string) ($_POST['peso_consulta'] ?? '')),
        'comentario' => trim((string) ($_POST['comentario_seguimiento'] ?? ''))
    ];
    if ($datos['cedula_usuario'] === '' || $datos['id_mascota'] < 1
        || $datos['fecha'] === '' || !in_array($datos['tipo_ingreso'], [1, 2], true)
        || !in_array($datos['remitido'], [0, 1], true)) {
        echo json_encode([
            'status' => 'error',
            'mensaje' => 'Complete los datos obligatorios de la consulta.'
        ]);
        exit;
    }
    $camposAnamnesis = ['motivo_consulta','inicio_enfermedad','examenes_efectuados','tratamientos_realizados',
        'ult_desp_interna','ult_desp_int_producto','ult_desp_externa','ult_desp_ext_producto','ultima_vacunacion',
        'tipo_alimentacion','frec_alimentacion','apetito','ingesta_agua','contacto_animal','vomito','heces',
        'miccion','fch_ultimo_celo','prod_higiene','frec_higiene','int_ambiente','ext_ambiente',
        'act_ectoparasitos','ant_ectoparasitos'];
    $camposExamen = ['peso','cc','temp_celsius','pulso_yugular','fr_rpm','fc_lpm','pulso_ppm','tlc_seg','tpc_seg',
        'pas','pad','prcnt_deshidratacion','gangliios_palpables','mucosas_visibles','ectoparasitos','actitud','hallazgos'];
    $camposLaboratorio = ['hematologia_completa','coprologia','quimica_sanguinea','uro_sangre','uro_urob','uro_bli',
        'uro_prot','uro_nitritos','uro_cetona','uro_glucosa','uro_ph','uro_leu','uro_densidad','uro_microorganismos',
        'uro_celulas','uro_cilindros','uro_cristales','descarte','piel_otros','snap','observaciones'];
    $anamnesis = [];
    $examen = [];
    $laboratorio = [];
    foreach ($camposAnamnesis as $campo) {
        $valor = $_POST[$campo] ?? null;
        $anamnesis[$campo] = $valor === '' ? null : $valor;
    }
    foreach ($camposExamen as $campo) {
        $valor = $_POST[$campo] ?? null;
        $examen[$campo] = $valor === '' ? null : $valor;
    }
    foreach ($camposLaboratorio as $campo) {
        $valor = $_POST[$campo] ?? null;
        $laboratorio[$campo] = $valor === '' ? null : $valor;
    }

    $diagnosticos = [];
    foreach ((array) ($_POST['id_patologia'] ?? []) as $indice => $patologia) {
        if ((int) $patologia > 0 && trim((string) ($_POST['tipo_diagnostico'][$indice] ?? '')) !== '') {
            $diagnosticos[] = [
                'id_diagnostico' => (int) ($_POST['id_diagnostico'][$indice] ?? 0),
                'id_patologia' => (int) $patologia,
                'tipo_diagnostico' => trim((string) $_POST['tipo_diagnostico'][$indice])
            ];
        }
    }
    $recetas = [];
    foreach ((array) ($_POST['id_medicamento'] ?? []) as $indice => $medicamento) {
        if ((int) $medicamento > 0) {
            $recetas[] = [
                'id_recipe' => (int) ($_POST['id_recipe'][$indice] ?? 0),
                'id_medicamento' => (int) $medicamento,
                'dosis' => trim((string) ($_POST['dosis'][$indice] ?? '')),
                'frecuencia' => trim((string) ($_POST['frecuencia'][$indice] ?? '')),
                'duracion' => trim((string) ($_POST['duracion'][$indice] ?? ''))
            ];
        }
    }
    $resultado = $idConsulta > 0
        ? $consultaModel->editarConsulta($idConsulta, $datos, $anamnesis, $examen, $laboratorio, $diagnosticos, $recetas)
        : $consultaModel->agregarConsulta($datos, $anamnesis, $examen, $laboratorio, $diagnosticos, $recetas);
    echo json_encode([
        'status' => $resultado ? 'success' : 'error',
        'mensaje' => $resultado ? 'La consulta se guardó correctamente.' : 'No fue posible guardar la consulta.',
        'redirect' => 'index.php?url=Consulta'
    ]);
    exit;
}

require_once 'app/view/NuevaConsultaView.php';
