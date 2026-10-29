<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\PresentacionModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;
use Gabriel\SistemaFarmovet\helpers\traits\Validation;

verificarRol([1, 2, 3]);



$PresentacionModel = new PresentacionModel();

$errors = [];

if(isset($_POST["obtener"])) {
    $pagina = (int) ($_POST["pagina"] ?? 1) ;
    $limitacion = (int) ($_POST["limite"] ?? 5);
    $limitacion = in_array($limitacion, [5, 10, 20], true) ? $limitacion : 5;

    if (isset($_POST["parametro"])) {
        $param = $_POST["parametro"];
        $resultados = $PresentacionModel->filtrarPresentacion($param, $pagina, $limitacion);
    } else {
        $resultados = $PresentacionModel->obtenerPresentacion($pagina, $limitacion);
    }
    $total = $PresentacionModel->contarPresentaciones(isset($_POST["parametro"]) ? $param : null);

    if ($resultados) {
        echo json_encode(["status" => "success", "resultados" => $resultados, "hasMore" => $pagina * $limitacion < $total]);
    } else {
        echo json_encode(["status" => "success", "resultados" => [], "hasMore" => false]);
    }
    exit;
}

if (isset($_POST["agregar"])) {
    verificarRol([1]);

    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $estado = (int) ($_POST["estado_presentacion"] ?? 1);
   

        $PresentacionModel->clearErrors();

        $PresentacionModel->validateRequired(['nombre' => $nombre], ['nombre']);
        $PresentacionModel->validateMinLength($nombre, 'nombre', 5);
        $PresentacionModel->validateMaxLength($nombre, 'nombre', 50);

        if ($estado !== 0 && $estado !== 1) {
            $PresentacionModel->addError(
                'estado_presentacion',
                'Debe ser activo o inactivo.'
            );
        }

        $errors = $PresentacionModel->getErrors();

        if (!empty($errors)) {
            echo json_encode([
                'status' => 'error',
                'errors' => $errors
            ]);
            exit;
        }
    
    $resultado = $PresentacionModel->agregarPresentacion($nombre, $estado);

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
    verificarRol([1]);

    $id = (int) ($_POST["id"] ?? 0);
    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $estado = (int) ($_POST["estado_presentacion"] ?? 1);
    
        $PresentacionModel->clearErrors();

        $PresentacionModel->validateRequired(['nombre' => $nombre], ['nombre']);
        $PresentacionModel->validateMinLength($nombre, 'nombre', 5);
        $PresentacionModel->validateMaxLength($nombre, 'nombre', 50);

        if ($estado !== 0 && $estado !== 1) {
            $PresentacionModel->addError(
                'estado_presentacion',
                'Debe ser activo o inactivo.'
            );
        }

        $errors = $PresentacionModel->getErrors();

        if (!empty($errors)) {
            echo json_encode([
                'status' => 'error',
                'errors' => $errors
            ]);
            exit;
        }
    $resultado = $PresentacionModel->actualizarPresentacion($id, $nombre, $estado);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if (isset($_POST["eliminar"])) {
    verificarRol([1]);

    $id = $_POST["id"] ?? 0;
    $resultado = $PresentacionModel->eliminarPresentacion($id);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

include_once "app/view/PresentacionView.php";