<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\PatologiaModel;

$patologiaModel = new PatologiaModel();

if (isset($_POST["obtener"])) {
    $pagina = $_POST["pagina"] ?? 1;
    $limitacion = $_POST["limite"] ?? 5;

    if (isset($_POST["parametro"])) {
        $param = $_POST["parametro"];
        $resultados = $patologiaModel->filtrarPatologia($param, $pagina, $limitacion);
    } else {
        $resultados = $patologiaModel->obtenerPatologias($pagina, $limitacion);
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
    $tipo = $_POST["tipo"] ?? "";
    $sintomas = $_POST["sintomas"] ?? "";

    if ($patologiaModel->agregarPatologia($nombre, $tipo, $sintomas)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

if (isset($_POST["obtenerPatologia"]) && isset($_POST["id"])) {
    $id = $_POST["id"];
    $resultado = $patologiaModel->obtenerPatologiaPorId($id);

    if ($resultado) {
        echo json_encode(["status" => "success", "resultado" => $resultado]);
    } else {
        echo json_encode(["status" => "error", "resultado" => []]);
    }
    exit;
}

if (isset($_POST["actualizar"])) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $sintomas = $_POST["sintomas"] ?? "";

    if ($patologiaModel->actualizarPatologia($id, $nombre, $tipo, $sintomas)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

if (isset($_POST["eliminar"]) && isset($_POST["id"])) {
    $id = $_POST["id"];

    if ($patologiaModel->eliminarPatologia($id)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

require_once "app/view/PatologiaView.php";
?>