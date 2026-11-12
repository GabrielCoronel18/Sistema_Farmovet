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

if (isset($_POST['obtener'])) {
    $pagina = max(1, (int) ($_POST["pagina"] ?? 1));
    $limitacion = (int) ($_POST["limite"] ?? 5);
    $limitacion = in_array($limitacion, [5, 10, 20], true) ? $limitacion : 5;

    $param = trim((string) ($_POST["parametro"] ?? ""));
    $tieneFiltro = $param !== "";

    $consultas = $tieneFiltro
        ? $consultaModel->filtrarConsultas($param, $pagina, $limitacion)
        : $consultaModel->obtenerConsultas($pagina, $limitacion);
    $total = $consultaModel->contarConsulta($tieneFiltro ? $param : null);

    
    foreach ($consultas as &$consulta) {
        $id = (int) $consulta['id_consulta'];
        $consulta['detalle'] = [
            'anamnesis' => (new AnamnesisModel())->obtenerAnamnesis($id),
            'examen' => (new ExamenClinicoModel())->obtenerExamen($id),
            'laboratorio' => (new ResultadosLaboratorioModel())->obtenerResultado($id),
            'diagnosticos' => (new DiagnosticoModel())->obtenerDiagnosticos($id),
            'recetas' => (new RecipeModel())->obtenerRecipes($id)
        ];
    }
    unset($consulta);
    echo json_encode(['status' => 'success', 'resultados' => $consultas,  "hayMas" => $pagina * $limitacion < $total]);
    exit;
}

if (isset($_POST['eliminar'], $_POST['id'])) {
    verificarRol([1,2]);
    
    $resultado = $consultaModel->eliminarConsulta((int) $_POST['id']);
    echo json_encode(['status' => $resultado ? 'success' : 'error']);
    exit;
}

require_once 'app/view/ConsultaView.php';
