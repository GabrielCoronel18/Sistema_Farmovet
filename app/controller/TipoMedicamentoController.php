<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\TipoMedicamentoModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$TipoMedicamentoModel = new TipoMedicamentoModel();
 

if(isset($_POST["obtener"])){
    $pagina = (int) ($_POST["pagina"] ?? 1);
    $limitacion = (int) ($_POST["limite"] ?? 5);
    $limitacion = in_array($limitacion, [5, 10, 20], true) ? $limitacion : 5;
    
    if(isset($_POST["parametro"])){
        $param = $_POST["parametro"];
        $resultados = $TipoMedicamentoModel->filtrarTipoMedicamento($pagina,$limitacion,$param);
    }
    else{
        $resultados = $TipoMedicamentoModel->obtenerTipoMedicamento($pagina,$limitacion);
    }
    $total = $TipoMedicamentoModel->contarTipoMedicamentos(isset($_POST["parametro"]) ? $param : null);

    if($resultados){
        echo json_encode(["status"=>"success","resultados" => $resultados, "hasMore" => $pagina * $limitacion < $total]);
    }
    else{
        echo json_encode(["status"=>"success","resultados" => [], "hasMore" => false]);
    }
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