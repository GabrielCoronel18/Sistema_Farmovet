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
    $total = $medicamentoModel->contarMedicamentos(isset($_POST["parametro"]) ? $param : null);

    echo json_encode([
        "status" => "success",
        "resultados" => $resultados,
        "hasMore" => $pagina * $limitacion < $total
    ]);
    exit;
}

if (isset($_POST["agregar"])) {
    verificarRol([1]);

    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $tipo = (int) ($_POST["tipo"] ?? 0);
    $presentacion = (int) ($_POST["presentacion"] ?? 0);
    $estado = (int) ($_POST["estado_medicamento"] ?? 1);

    $medicamentoModel->clearErrors();
    
    $tiposDisponibles = $medicamentoModel->obtenerCatalogoTipos();
    $presentacionesDisponibles = $medicamentoModel->obtenerCatalogoPresentaciones();

    $medicamentoModel->validateRequired(['nombre' => $nombre, 'tipo' => $tipo, 'presentacion' => $presentacion, 'estado' => $estado], ['nombre', 'tipo', 'presentacion', 'estado']);
    $medicamentoModel->validateMinLength($nombre, 'nombre', 5);
    $medicamentoModel->validateMaxLength($nombre, 'nombre', 50);
    $medicamentoModel->validateInArray($tipo, 'tipo', array_column($tiposDisponibles, 'id_tipo_medicamento'));  
    $medicamentoModel->validateInArray($presentacion, 'presentacion', array_column($presentacionesDisponibles, 'id_presentacion'));
    
    
    if ($estado !== 0 && $estado !== 1) {
        $medicamentoModel->addError('estado', 'Debe ser activo o inactivo.');
    }

    $errors = $medicamentoModel->getErrors();
    if(!empty($errors)) {
        echo json_encode([
            "status" => "error",
            "errors" => $errors
        ]);
        exit;
    }
    $resultado = $medicamentoModel->agregarMedicamento($nombre, $tipo, $presentacion, $estado);
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

    $id = (int) ($_POST["id"] ?? 0);
    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $tipo = (int) ($_POST["tipo"] ?? 0);
    $presentacion = (int) ($_POST["presentacion"] ?? 0);
    $estado = (int) ($_POST["estado_medicamento"] ?? 1);
    
    $tiposDisponibles = $medicamentoModel->obtenerCatalogoTipos();
    $presentacionesDisponibles = $medicamentoModel->obtenerCatalogoPresentaciones();

    $medicamentoModel->clearErrors();
    $medicamentoModel->validateRequired(['nombre' => $nombre, 'tipo' => $tipo, 'presentacion' => $presentacion, 'estado' => $estado], ['nombre', 'tipo', 'presentacion', 'estado']);
    $medicamentoModel->validateMinLength($nombre, 'nombre', 3);
    $medicamentoModel->validateMaxLength($nombre, 'nombre', 50);
    $medicamentoModel->validateInArray($tipo, 'tipo', array_column($tiposDisponibles, 'id_tipo_medicamento'));  
    $medicamentoModel->validateInArray($presentacion, 'presentacion', array_column($presentacionesDisponibles, 'id_presentacion'));

    if ($estado !== 0 && $estado !== 1) {
        $medicamentoModel->addError('estado', 'Debe ser activo o inactivo.');
    }

    $errors = $medicamentoModel->getErrors();
    if(!empty($errors)) {
        echo json_encode([
            "status" => "error",
            "errors" => $errors
        ]);
        exit;
    }


    $resultado = $medicamentoModel->actualizarMedicamento($id, $nombre, $tipo, $presentacion, $estado);
        
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if(isset($_POST["eliminar"], $_POST["id"])) {
    verificarRol([1]);

    $resultado = $medicamentoModel->eliminarMedicamento($_POST["id"]);
    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}


if (isset($_POST["obtenerTipos"])) {
    $resultado = $medicamentoModel->obtenerCatalogoTipos();

    echo json_encode([
        "status" => "success",
        "resultado" => $resultado
    ]);
    exit;
}

if (isset($_POST["obtenerPresentaciones"])) {
    $resultado = $medicamentoModel->obtenerCatalogoPresentaciones();

    echo json_encode([
        "status" => "success",
        "resultado" => $resultado
    ]);
    exit;
}

include_once "app/view/MedicamentoView.php";
