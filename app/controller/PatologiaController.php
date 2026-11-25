<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\PatologiaModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2]);

$patologiaModel = new PatologiaModel();

if (isset($_POST["obtener"])) {
    
   verificarRol([1, 2]);
   
    $draw   = (int) ($_POST["draw"] ?? 1);
    $start  = max(0, (int) ($_POST["start"] ?? 0));
    $length = (int) ($_POST["length"] ?? 5);
    $length = in_array($length, [5, 10, 20], true) ? $length : 5;

    $pagina = (int) floor($start / $length) + 1;
    
    $param = trim((string) ($_POST["search"]["value"] ?? ""));
    
    $tieneFiltro = $param !== "";

  $resultados = $tieneFiltro
        ? $patologiaModel->filtrarPatologia($param, $pagina, $length)
        : $patologiaModel->obtenerPatologias($pagina, $length);

    $recordsTotal = $patologiaModel->contarPatologias(null);
    $recordsFiltered = $tieneFiltro
        ? $patologiaModel->contarPatologias($param)
        : $recordsTotal;

    echo json_encode([
        "draw"            => $draw,
        "recordsTotal"    => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data"            => $resultados
    ]);
    exit;
}

if (isset($_POST["agregar"])) {
    verificarRol([1]);

    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $tipo = trim((string) ($_POST["tipo"] ?? ""));
    $sintomas = trim((string) ($_POST["sintomas"] ?? ""));
    $estado = (int) ($_POST["estado_patologia"] ?? 1);

    $patologiaModel->clearErrors();
    
	
	$patologiaModel->validateRequired(['nombre' => $nombre, 'tipo' => $tipo, 'sintomas' => $sintomas, 'estado' => $estado], ['nombre', 'tipo', 'sintomas', 'estado']);
	
	$patologiaModel->validateMinLength($nombre, 'nombre', 5);
	$patologiaModel->validateMaxLength($nombre, 'nombre', 50);
    $patologiaModel->validateNombrePatologia($nombre);

    $patologiaModel->validateMinLength($tipo, 'tipo', 5);
	$patologiaModel->validateMaxLength($tipo, 'tipo', 50);
    $patologiaModel->validateTipoPatologia($tipo);
    
    $patologiaModel->validateMinLength($sintomas, 'sintomas', 5);
	$patologiaModel->validateMaxLength($sintomas, 'sintomas', 200);
    $patologiaModel->validateSintomasPatologia($sintomas);
	
	if ($estado !== 0 && $estado !== 1) {
		$patologiaModel->addError('estado', 'Debe ser activo o inactivo.');
	}

	$errors = $patologiaModel->getErrors();
	if(!empty($patologiaModel->getErrors())) {
		echo json_encode([
			"status" => "error",
			"errors" => $errors
		]);
		exit;
	}
    

    $resultado =  $patologiaModel->agregarPatologia($nombre, $tipo, $sintomas, $estado);

    echo json_encode(["status" => $resultado ? "success" : "error"]);
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
    verificarRol([1]);

    $id = (int) ($_POST["id"] ?? 0);
    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $tipo = trim((string) ($_POST["tipo"] ?? ""));
    $sintomas = trim((string) ($_POST["sintomas"] ?? ""));
    $estado = (int) ($_POST["estado_patologia"] ?? 1);

    $patologiaModel->clearErrors();
    
	
	$patologiaModel->validateRequired(['nombre' => $nombre, 'tipo' => $tipo, 'sintomas' => $sintomas, 'estado' => $estado], ['nombre', 'tipo', 'sintomas', 'estado']);
	
	$patologiaModel->validateMinLength($nombre, 'nombre', 5);
	$patologiaModel->validateMaxLength($nombre, 'nombre', 50);
	$patologiaModel->validateNombrePatologia($nombre);
    $patologiaModel->validateMinLength($tipo, 'tipo', 5);
	$patologiaModel->validateMaxLength($tipo, 'tipo', 50);
    $patologiaModel->validateTipoPatologia($tipo);
    
    $patologiaModel->validateMinLength($sintomas, 'sintomas', 5);
	$patologiaModel->validateMaxLength($sintomas, 'sintomas', 200);
    $patologiaModel->validateSintomasPatologia($sintomas);
	
	if ($estado !== 0 && $estado !== 1) {
		$patologiaModel->addError('estado', 'Debe ser activo o inactivo.');
	}

	$errors = $patologiaModel->getErrors();
	if(!empty($patologiaModel->getErrors())) {
		echo json_encode([
			"status" => "error",
			"errors" => $errors
		]);
		exit;
	}

    $resultado =  $patologiaModel->actualizarPatologia($id, $nombre, $tipo, $sintomas, $estado);

    echo json_encode(["status" => $resultado ? "success" : "error"]);
    exit;
}

if (isset($_POST["eliminar"]) && isset($_POST["id"])) {
    verificarRol([1]);

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