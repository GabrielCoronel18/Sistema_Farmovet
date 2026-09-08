<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\PresentacionModel;

$PresentacionModel = new PresentacionModel();

if(isset($_POST["obtener"])) {
    $pagina = $_POST["pagina"] ?? 1;
    $limitacion = $_POST["limite"] ?? 5;

    if (isset($_POST["parametro"])) {
        $param = $_POST["parametro"];
        $resultados = $PresentacionModel->filtrarPresentacion($param, $pagina, $limitacion);
    } else {
        $resultados = $PresentacionModel->obtenerPresentacion($pagina, $limitacion);
    }

    if ($resultados) {
        echo json_encode(["status" => "success", "resultados" => $resultados]);
    } else {
        echo json_encode(["status" => "error", "resultados" => []]);
    }
    exit;
}

if (isset($_POST["agregar"])) {
    $nombre = $_POST["nombre"] ?? "";
    $resultado = $PresentacionModel->agregarPresentacion($nombre);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["obtenerPresentacion"], $_POST["id"])) {
    $id = $_POST["id"] ?? 0;
    $resultado = $PresentacionModel->obtenerPresentacionPorId($id);
    echo json_encode([
        "status" => $resultado ? "success" : "error", "resultado" => $resultado ?: []]);
    exit;
}

if (isset($_POST["actualizar"])) {
    $id = $_POST["id"] ?? 0;
    $nombre = $_POST["nombre"] ?? "";
    $resultado = $PresentacionModel->actualizarPresentacion($id, $nombre);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if (isset($_POST["eliminar"])) {
    $id = $_POST["id"] ?? 0;
    $resultado = $PresentacionModel->eliminarPresentacion($id);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

include_once "app/view/PresentacionView.php";