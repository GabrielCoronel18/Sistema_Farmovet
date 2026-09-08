<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\TipoMedicamentoModel;

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

    if($resultados){
        echo json_encode(["status"=>"success","resultados" => $resultados]);
    }
    else{
        echo json_encode(["status"=>"error","resultados" => []]);
    }
  exit;

}

if(isset($_POST["agregar"])){
    $nombre = $_POST["nombre"] ?? "";
    $resultado = $TipoMedicamentoModel->agregarTipoMedicamento($nombre);
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
    $id = $_POST["id"] ?? 0;
    $nombre = $_POST["nombre"] ?? "";
    $resultado = $TipoMedicamentoModel->actualizarTipoMedicamento($id,$nombre);
    echo json_encode(["status"=>$resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["eliminar"])){
    $id = $_POST["id"] ?? 0;
    $resultado = $TipoMedicamentoModel->eliminarTipoMedicamento($id);
    echo json_encode(["status"=>$resultado ? "success" : "error"]);
    exit;
}

include_once "app/view/TipoMedicamentoView.php";