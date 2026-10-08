<?php
use Gabriel\SistemaFarmovet\model\ConsultaModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$consultaModel = new ConsultaModel();
$consultas = [];
$consultaError = '';

$responderAjax = static function (array $respuesta, int $codigo = 200): void {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['obtener'])) {
    try {
        $consultas = $consultaModel->obtenerConsultas();
        if ($consultas) {
            $detalles = $consultaModel->obtenerDetalles(array_column($consultas, 'id_consulta'));
            foreach ($consultas as &$consulta) {
                $consulta['detalle'] = $detalles[(int) $consulta['id_consulta']];
            }
            unset($consulta);
        }
        $responderAjax(['status' => 'success', 'resultados' => $consultas]);
    } catch (Throwable $e) {
        error_log('Error al consultar historial por AJAX: ' . $e->getMessage());
        $responderAjax(['status' => 'error', 'mensaje' => 'No fue posible cargar las consultas.'], 500);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_consulta'])) {
    $entradaId = $_POST['id_consulta'] ?? null;
    $idConsulta = is_scalar($entradaId) ? filter_var($entradaId, FILTER_VALIDATE_INT) : false;
    if (!$idConsulta || $idConsulta < 1) {
        $responderAjax(['status' => 'error', 'mensaje' => 'La consulta seleccionada no es válida.'], 422);
    }
    try {
        if (!$consultaModel->eliminarConsulta($idConsulta)) {
            $responderAjax(['status' => 'error', 'mensaje' => 'La consulta no existe o ya fue eliminada.'], 404);
        }
        $responderAjax(['status' => 'success', 'mensaje' => 'La consulta se eliminó correctamente.']);
    } catch (Throwable $e) {
        error_log('Error al eliminar consulta: ' . $e->getMessage());
        $responderAjax(['status' => 'error', 'mensaje' => 'No se pudo eliminar la consulta. Inténtelo nuevamente.'], 500);
    }
}

try {
    $consultas = $consultaModel->obtenerConsultas();
    if ($consultas) {
        $detalles = $consultaModel->obtenerDetalles(array_column($consultas, 'id_consulta'));
        foreach ($consultas as &$consulta) {
            $consulta['detalle'] = $detalles[(int) $consulta['id_consulta']];
        }
        unset($consulta);
    }
} catch (Throwable $e) {
    error_log('Error al cargar consultas: ' . $e->getMessage());
    $consultaError = 'No fue posible cargar las consultas registradas.';
}

require_once "app/view/ConsultaView.php";