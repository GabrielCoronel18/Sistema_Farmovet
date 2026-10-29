<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\PatologiaModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

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
    verificarRol([1]);

    $nombre = trim((string) ($_POST["nombre"] ?? ""));
    $tipo = trim((string) ($_POST["tipo"] ?? ""));
    $sintomas = trim((string) ($_POST["sintomas"] ?? ""));
    $estado = (int) ($_POST["estado_patologia"] ?? 1);

    $patologiaModel->clearErrors();
    
	
	$patologiaModel->validateRequired(['nombre' => $nombre, 'tipo' => $tipo, 'sintomas' => $sintomas, 'estado' => $estado], ['nombre', 'tipo', 'sintomas', 'estado']);
	
	$patologiaModel->validateMinLength($nombre, 'nombre', 5);
	$patologiaModel->validateMaxLength($nombre, 'nombre', 50);
	
    $patologiaModel->validateMinLength($tipo, 'tipo', 5);
	$patologiaModel->validateMaxLength($tipo, 'tipo', 50);
    
    $patologiaModel->validateMinLength($sintomas, 'sintomas', 5);
	$patologiaModel->validateMaxLength($sintomas, 'sintomas', 50);
	
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
	
    $patologiaModel->validateMinLength($tipo, 'tipo', 5);
	$patologiaModel->validateMaxLength($tipo, 'tipo', 50);
    
    $patologiaModel->validateMinLength($sintomas, 'sintomas', 5);
	$patologiaModel->validateMaxLength($sintomas, 'sintomas', 50);
	
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