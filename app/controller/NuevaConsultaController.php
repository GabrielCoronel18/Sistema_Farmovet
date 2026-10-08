<?php
use Gabriel\SistemaFarmovet\model\ConsultaModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$consultaModel = new ConsultaModel();
$consultaError = '';
$idConsultaSolicitado = $_POST['id_consulta'] ?? $_GET['id_consulta'] ?? null;
$idConsultaEdicion = filter_var(
    $idConsultaSolicitado,
    FILTER_VALIDATE_INT
);
$idConsultaEdicion = $idConsultaEdicion && $idConsultaEdicion > 0 ? $idConsultaEdicion : 0;
$consultaEdicion = null;
$consultaValores = [];
$diagnosticosForm = [];
$recetasForm = [];
$responderAjax = static function (array $respuesta, int $codigo = 200): void {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
};
if ($idConsultaSolicitado !== null && $idConsultaSolicitado !== ''
    && (!$idConsultaEdicion || !is_scalar($idConsultaSolicitado))) {
    $consultaError = 'El identificador de consulta no es válido.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($consultaError !== '') {
            throw new InvalidArgumentException($consultaError);
        }
        if ($idConsultaEdicion > 0) {
            $consultaEdicion = $consultaModel->obtenerConsultaParaEditar($idConsultaEdicion);
            if (!$consultaEdicion) {
                throw new InvalidArgumentException('La consulta que intenta editar ya no está disponible.');
            }
        }
        $obtenerEntrada = static function (string $clave): string {
            $valor = $_POST[$clave] ?? '';
            if (!is_scalar($valor)) {
                throw new InvalidArgumentException('Se recibieron datos de consulta con un formato inválido.');
            }
            return trim((string) $valor);
        };
        $idMascota = filter_var($obtenerEntrada('id_mascota'), FILTER_VALIDATE_INT);
        $tipoIngreso = filter_var($obtenerEntrada('tipo_ingreso'), FILTER_VALIDATE_INT);
        $remitido = filter_var($obtenerEntrada('remitido'), FILTER_VALIDATE_INT);
        $fecha = $obtenerEntrada('fecha');
        $fechaValidada = DateTime::createFromFormat('!Y-m-d', $fecha);

        if (!$idMascota || !in_array($tipoIngreso, [1, 2], true)
            || !in_array($remitido, [0, 1], true)
            || !$fechaValidada || $fechaValidada->format('Y-m-d') !== $fecha) {
            throw new InvalidArgumentException('Seleccione una mascota, fecha, tipo de ingreso y remitente válidos.');
        }

        $obtenerValor = static function (string $clave) use ($obtenerEntrada): ?string {
            $valor = $obtenerEntrada($clave);
            return $valor === '' ? null : $valor;
        };
        $convertirDatos = static function (array $claves) use ($obtenerValor): array {
            $resultado = [];
            foreach ($claves as $clave) {
                $resultado[$clave] = $obtenerValor($clave);
            }
            return $resultado;
        };
        $leerColeccion = static function (string $clave): array {
            $valores = $_POST[$clave] ?? [];
            if (!is_array($valores)) {
                throw new InvalidArgumentException('Se recibieron datos de consulta con un formato inválido.');
            }
            return $valores;
        };

        $consulta = [
            'cedula_usuario' => (string) ($_SESSION['usuario']['cedula_usuario'] ?? ''),
            'id_mascota' => $idMascota,
            'fecha' => $fecha,
            'tipo_ingreso' => $tipoIngreso,
            'remitido' => $remitido,
            'pronostico' => $obtenerValor('pronostico'),
            'tratamiento_consulta' => $obtenerValor('tratamiento_consulta'),
            'peso_consulta' => $obtenerValor('peso_consulta'),
            'comentario' => $obtenerValor('comentario_seguimiento')
        ];

        $anamnesis = $convertirDatos([
            'motivo_consulta', 'inicio_enfermedad', 'examenes_efectuados', 'tratamientos_realizados',
            'ult_desp_interna', 'ult_desp_int_producto', 'ult_desp_externa', 'ult_desp_ext_producto',
            'ultima_vacunacion', 'tipo_alimentacion', 'frec_alimentacion', 'apetito', 'ingesta_agua',
            'contacto_animal', 'vomito', 'heces', 'miccion', 'fch_ultimo_celo', 'prod_higiene',
            'frec_higiene', 'int_ambiente', 'ext_ambiente'
        ]);
        $anamnesis['act_ectoparasitos'] = $obtenerValor('act_ectoparasitos');
        $anamnesis['ant_ectoparasitos'] = $obtenerValor('ant_ectoparasitos');

        $examen = $convertirDatos([
            'peso', 'cc', 'temp_celsius', 'pulso_yugular', 'fr_rpm', 'fc_lpm', 'pulso_ppm',
            'tlc_seg', 'tpc_seg', 'pas', 'pad', 'prcnt_deshidratacion', 'gangliios_palpables',
            'mucosas_visibles', 'ectoparasitos', 'actitud', 'hallazgos'
        ]);
        $laboratorio = $convertirDatos([
            'hematologia_completa', 'coprologia', 'quimica_sanguinea', 'uro_sangre', 'uro_urob',
            'uro_bli', 'uro_prot', 'uro_nitritos', 'uro_cetona', 'uro_glucosa', 'uro_ph', 'uro_leu',
            'uro_densidad', 'uro_microorganismos', 'uro_celulas', 'uro_cilindros', 'uro_cristales',
            'descarte', 'piel_otros', 'snap', 'observaciones'
        ]);

        $diagnosticos = [];
        $idsPatologia = $leerColeccion('id_patologia');
        $tiposDiagnostico = $leerColeccion('tipo_diagnostico');
        if (count($idsPatologia) !== count($tiposDiagnostico)) {
            throw new InvalidArgumentException('Revise los diagnósticos ingresados.');
        }
        foreach ($idsPatologia as $indice => $idPatologia) {
            if (!is_scalar($idPatologia) || !is_scalar($tiposDiagnostico[$indice] ?? '')) {
                throw new InvalidArgumentException('Revise los diagnósticos ingresados.');
            }
            $idPatologia = trim((string) $idPatologia);
            $tipoDiagnostico = trim((string) ($tiposDiagnostico[$indice] ?? ''));
            if ($idPatologia === '' && $tipoDiagnostico === '') {
                continue;
            }
            $idPatologiaValidado = filter_var($idPatologia, FILTER_VALIDATE_INT);
            if (!$idPatologiaValidado || $tipoDiagnostico === '') {
                throw new InvalidArgumentException('Cada diagnóstico requiere una patología y un tipo.');
            }
            $diagnosticos[] = [
                'id_patologia' => $idPatologiaValidado,
                'tipo_diagnostico' => $tipoDiagnostico
            ];
        }

        $recetas = [];
        $idsMedicamento = $leerColeccion('id_medicamento');
        $dosis = $leerColeccion('dosis');
        $frecuencias = $leerColeccion('frecuencia');
        $duraciones = $leerColeccion('duracion');
        if (count($idsMedicamento) !== count($dosis)
            || count($idsMedicamento) !== count($frecuencias)
            || count($idsMedicamento) !== count($duraciones)) {
            throw new InvalidArgumentException('Revise los medicamentos recetados.');
        }
        foreach ($idsMedicamento as $indice => $idMedicamento) {
            if (!is_scalar($idMedicamento)
                || !is_scalar($dosis[$indice] ?? '')
                || !is_scalar($frecuencias[$indice] ?? '')
                || !is_scalar($duraciones[$indice] ?? '')) {
                throw new InvalidArgumentException('Revise los medicamentos recetados.');
            }
            $idMedicamento = trim((string) $idMedicamento);
            $datosReceta = [
                'dosis' => trim((string) ($dosis[$indice] ?? '')),
                'frecuencia' => trim((string) ($frecuencias[$indice] ?? '')),
                'duracion' => trim((string) ($duraciones[$indice] ?? ''))
            ];
            if ($idMedicamento === '' && implode('', $datosReceta) === '') {
                continue;
            }
            $idMedicamentoValidado = filter_var($idMedicamento, FILTER_VALIDATE_INT);
            if (!$idMedicamentoValidado || in_array('', $datosReceta, true)) {
                throw new InvalidArgumentException('Cada medicamento requiere dosis, frecuencia y duración.');
            }
            $recetas[] = ['id_medicamento' => $idMedicamentoValidado] + $datosReceta;
        }

        if ($idConsultaEdicion > 0) {
            $consultaModel->actualizarConsulta(
                $idConsultaEdicion,
                $consulta,
                $anamnesis,
                $examen,
                $laboratorio,
                $diagnosticos,
                $recetas
            );
            $responderAjax([
                'status' => 'success',
                'mensaje' => 'La consulta se actualizó correctamente.',
                'id_consulta' => $idConsultaEdicion,
                'redirect' => 'index.php?url=Consulta'
            ]);
        } else {
            $idConsultaGuardada = $consultaModel->registrarConsulta(
                $consulta,
                $anamnesis,
                $examen,
                $laboratorio,
                $diagnosticos,
                $recetas
            );
            $responderAjax([
                'status' => 'success',
                'mensaje' => 'La consulta se guardó correctamente.',
                'id_consulta' => $idConsultaGuardada,
                'redirect' => 'index.php?url=Consulta'
            ]);
        }
    } catch (InvalidArgumentException $e) {
        $responderAjax(['status' => 'error', 'mensaje' => $e->getMessage()], 422);
    } catch (Throwable $e) {
        error_log('Error al guardar consulta: ' . $e->getMessage());
        $responderAjax([
            'status' => 'error',
            'mensaje' => 'No se pudo guardar la consulta. Verifique los datos e inténtelo nuevamente.'
        ], 500);
    }
}

try {
    if ($idConsultaEdicion > 0 && !$consultaEdicion) {
        $consultaEdicion = $consultaModel->obtenerConsultaParaEditar($idConsultaEdicion);
        if (!$consultaEdicion) {
            $consultaError = 'La consulta solicitada no existe o ya fue eliminada.';
            $idConsultaEdicion = 0;
        }
    }
    if ($consultaEdicion) {
        $consultaValores = array_merge(
            $consultaEdicion['consulta'],
            $consultaEdicion['detalle']['anamnesis'],
            $consultaEdicion['detalle']['examen'],
            $consultaEdicion['detalle']['laboratorio']
        );
        $consultaValores['comentario_seguimiento'] = $consultaEdicion['consulta']['comentario'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idsPatologia = $_POST['id_patologia'] ?? [];
            $tiposDiagnostico = $_POST['tipo_diagnostico'] ?? [];
            foreach ((array) $idsPatologia as $indice => $idPatologia) {
                $diagnosticosForm[] = [
                    'id_patologia' => is_scalar($idPatologia) ? (string) $idPatologia : '',
                    'tipo_diagnostico' => is_scalar($tiposDiagnostico[$indice] ?? '') ? (string) ($tiposDiagnostico[$indice] ?? '') : ''
                ];
            }
            $idsMedicamento = $_POST['id_medicamento'] ?? [];
            $dosis = $_POST['dosis'] ?? [];
            $frecuencias = $_POST['frecuencia'] ?? [];
            $duraciones = $_POST['duracion'] ?? [];
            foreach ((array) $idsMedicamento as $indice => $idMedicamento) {
                $recetasForm[] = [
                    'id_medicamento' => is_scalar($idMedicamento) ? (string) $idMedicamento : '',
                    'dosis' => is_scalar($dosis[$indice] ?? '') ? (string) ($dosis[$indice] ?? '') : '',
                    'frecuencia' => is_scalar($frecuencias[$indice] ?? '') ? (string) ($frecuencias[$indice] ?? '') : '',
                    'duracion' => is_scalar($duraciones[$indice] ?? '') ? (string) ($duraciones[$indice] ?? '') : ''
                ];
            }
        } else {
            $diagnosticosForm = $consultaEdicion['detalle']['diagnosticos'];
            $recetasForm = $consultaEdicion['detalle']['recetas'];
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $consultaValores = $_POST;
        $idsPatologia = $_POST['id_patologia'] ?? [''];
        $tiposDiagnostico = $_POST['tipo_diagnostico'] ?? [''];
        foreach ((array) $idsPatologia as $indice => $idPatologia) {
            $diagnosticosForm[] = [
                'id_patologia' => is_scalar($idPatologia) ? (string) $idPatologia : '',
                'tipo_diagnostico' => is_scalar($tiposDiagnostico[$indice] ?? '') ? (string) ($tiposDiagnostico[$indice] ?? '') : ''
            ];
        }
        $idsMedicamento = $_POST['id_medicamento'] ?? [''];
        $dosis = $_POST['dosis'] ?? [''];
        $frecuencias = $_POST['frecuencia'] ?? [''];
        $duraciones = $_POST['duracion'] ?? [''];
        foreach ((array) $idsMedicamento as $indice => $idMedicamento) {
            $recetasForm[] = [
                'id_medicamento' => is_scalar($idMedicamento) ? (string) $idMedicamento : '',
                'dosis' => is_scalar($dosis[$indice] ?? '') ? (string) ($dosis[$indice] ?? '') : '',
                'frecuencia' => is_scalar($frecuencias[$indice] ?? '') ? (string) ($frecuencias[$indice] ?? '') : '',
                'duracion' => is_scalar($duraciones[$indice] ?? '') ? (string) ($duraciones[$indice] ?? '') : ''
            ];
        }
    }
    $mascotas = $consultaModel->obtenerMascotasActivas();
    $patologias = $consultaModel->obtenerPatologiasActivas();
    $medicamentos = $consultaModel->obtenerMedicamentosActivos();
} catch (Throwable $e) {
    error_log('Error al cargar catálogos para consulta: ' . $e->getMessage());
    $mascotas = $patologias = $medicamentos = [];
    $consultaError = 'No fue posible cargar los datos necesarios para registrar la consulta.';
}

require_once "app/view/NuevaConsultaView.php";