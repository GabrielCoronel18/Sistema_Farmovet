<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\TipoMedicamentoModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2]);

$TipoMedicamentoModel = new TipoMedicamentoModel();
 

if(isset($_POST["obtener"])){
    verificarRol([1, 2]);
    
    $draw   = (int) ($_POST["draw"] ?? 1);
    $start  = max(0, (int) ($_POST["start"] ?? 0));
    $length = (int) ($_POST["length"] ?? 5);
    $length = in_array($length, [5, 10, 20], true) ? $length : 5;
    
    $pagina = (int) floor($start / $length) + 1;

    $param = trim((string) ($_POST["search"]["value"] ?? ""));

    $tieneFiltro = $param !== "";
    
    $recordsTotal = $TipoMedicamentoModel->contarTipoMedicamentos(null);

    if($tieneFiltro){
        $resultados = $TipoMedicamentoModel->filtrarTipoMedicamento($pagina,$length,$param);
        
        $recordsFiltered = $TipoMedicamentoModel->contarTipoMedicamentos($param);
    }
    else{
        $resultados = $TipoMedicamentoModel->obtenerTipoMedicamento($pagina,$length);
        $recordsFiltered = $recordsTotal;
    }
    

echo json_encode([
        "draw"            => $draw,
        "recordsTotal"    => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data"            => $resultados
    ]);
    exit;

}

if(isset($_POST["agregar"])){
    verificarRol([1]);

    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $estado = (int) ($_POST["estado_tipo_medicamento"] ?? 1);
    
    $TipoMedicamentoModel->clearErrors();

    $TipoMedicamentoModel->validateRequired(['nombre' => $nombre, 'estado' => $estado], ['nombre', 'estado']);
    
    $TipoMedicamentoModel->validateMinLength($nombre, 'nombre', 5);
    $TipoMedicamentoModel->validateMaxLength($nombre, 'nombre', 50);
    $TipoMedicamentoModel->validateNombreTipoMedicamento($nombre);

    if ($estado !== 0 && $estado !== 1) {
        $TipoMedicamentoModel->addError(
            'estado_tipo_medicamento',
            'Debe ser activo o inactivo.'
        );
    }
    $errors = $TipoMedicamentoModel->getErrors();

    if (!empty($errors)) {
        echo json_encode([
            'status' => 'error',
            'errors' => $errors
        ]);
        exit;
    }

    
    $resultado = $TipoMedicamentoModel->agregarTipoMedicamento($nombre, $estado);
    echo json_encode(["status"=>$resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["obtenerTipoMedicamento"], $_POST["id"])) {
    $resultado = $TipoMedicamentoModel->obtenerTipoMedicamentoPorId((int) $_POST["id"]);
    echo json_encode([
        "status" => $resultado ? "success" : "error",
        "resultado" => $resultado ?: []
    ]);
    exit;
}

if(isset($_POST["actualizar"])){
    verificarRol([1]);

    $id = (int) ($_POST["id"] ?? 0);
    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $estado = (int) ($_POST["estado_tipo_medicamento"] ?? 1);
    
     $TipoMedicamentoModel->clearErrors();

    $TipoMedicamentoModel->validateRequired(['nombre' => $nombre, 'estado' => $estado], ['nombre', 'estado']);
    
    $TipoMedicamentoModel->validateMinLength($nombre, 'nombre', 5);
    $TipoMedicamentoModel->validateMaxLength($nombre, 'nombre', 50);
    $TipoMedicamentoModel->validateNombreTipoMedicamento($nombre);

    if ($estado !== 0 && $estado !== 1) {
        $TipoMedicamentoModel->addError(
            'estado_tipo_medicamento',
            'Debe ser activo o inactivo.'
        );
    }
    $errors = $TipoMedicamentoModel->getErrors();

    if (!empty($errors)) {
        echo json_encode([
            'status' => 'error',
            'errors' => $errors
        ]);
        exit;
    }

    $resultado = $TipoMedicamentoModel->actualizarTipoMedicamento($id, $nombre, $estado);
    echo json_encode(["status"=>$resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["eliminar"])){
    verificarRol([1]);

    $id = $_POST["id"] ?? 0;
    $resultado = $TipoMedicamentoModel->eliminarTipoMedicamento($id);
    echo json_encode(["status"=>$resultado ? "success" : "error"]);
    exit;
}

include_once "app/view/TipoMedicamentoView.php";