<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\MedicamentoModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$medicamentoModel = new MedicamentoModel();


if (isset($_POST["obtener"])) {
    $pagina = (int) ($_POST["pagina"] ?? 1) ;
    $limitacion = (int) ($_POST["limite"] ?? 5);
    $limitacion = in_array($limitacion, [5, 10, 20], true) ? $limitacion : 5;

    $param = $_POST["parametro"] ?? "";

    $resultados = isset($_POST["parametro"])
        ? $medicamentoModel->filtrarMedicamento($param, $pagina, $limitacion)
        : $medicamentoModel->obtenerMedicamento($pagina, $limitacion);

    echo json_encode([ "status" => $resultados ? "success" : "error", "resultados" => $resultados ?: []]);
    exit;
}

if (isset($_POST["agregar"])) {
    verificarRol([1]);

    $nombre = $_POST["nombre"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $presentacion = $_POST["presentacion"] ?? "";

    $resultado = $medicamentoModel->agregarMedicamento($nombre, $tipo, $presentacion);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if (isset($_POST["obtenerMedicamento"], $_POST["id"])) {
    $resultado = $medicamentoModel->obtenerMedicamentoPorId($_POST["id"]);
    echo json_encode([ "status" => $resultado ? "success" : "error", "resultado" => $resultado ?: []]);
    exit;
}

if (isset($_POST["actualizar"], $_POST["id"])) {
    verificarRol([1]);

    $nombre = $_POST["nombre"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $presentacion = $_POST["presentacion"] ?? "";

    $resultado = $medicamentoModel->actualizarMedicamento($_POST["id"], $nombre, $tipo, $presentacion);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["eliminar"], $_POST["id"])) {
    verificarRol([1]);

    $resultado = $medicamentoModel->eliminarMedicamento($_POST["id"]);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["obtenerTipos"])) {
    $resultado = $medicamentoModel->obtenerCatalogoTipos();
    echo json_encode(["status" => $resultado ? "success" : "error", "resultado" => $resultado ?: []]);
    exit;
}

if(isset($_POST["obtenerPresentaciones"])) {
    $resultado = $medicamentoModel->obtenerCatalogoPresentaciones();
    echo json_encode(["status" => $resultado ? "success" : "error", "resultado" => $resultado ?: []]);
    exit;
}

include_once "app/view/MedicamentoView.php";
