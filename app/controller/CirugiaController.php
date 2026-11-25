<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\CirugiaModel;

use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2]);

$cirugiaModel = new CirugiaModel();

$errors = [];

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
        ? $cirugiaModel->filtrarCirugia($param, $pagina, $length)
        : $cirugiaModel->obtenerCirugias($pagina, $length);

    $recordsTotal = $cirugiaModel->contarCirugias(null);
    $recordsFiltered = $tieneFiltro
        ? $cirugiaModel->contarCirugias($param)
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

	$nombre = trim((string) ($_POST["nombre_cirugia"] ?? ""));
	$gravedad = trim((string) ($_POST["gravedad"] ?? ""));
	$estado = (int) $_POST["estado"];
	
	$cirugiaModel->clearErrors();
	
	$cirugiaModel->validateRequired(['nombre' => $nombre, 'gravedad' => $gravedad, 'estado' => $estado], ['nombre', 'gravedad', 'estado']);
	
	$cirugiaModel->validateMinLength($nombre, 'nombre', 5);
	$cirugiaModel->validateMaxLength($nombre, 'nombre', 50);
	$cirugiaModel->validateNombreCirugia($nombre);

	$cirugiaModel->validateInArray($gravedad, 'gravedad', ['Leve', 'Moderada', 'Grave']);
	

	if ($estado !== 0 && $estado !== 1) {
		$cirugiaModel->addError('estado', 'Debe ser activo o inactivo.');
	}
	$errors = $cirugiaModel->getErrors();

	if(!empty($cirugiaModel->getErrors())) {
		echo json_encode([
			"status" => "error",
			"errors" => $errors
		]);
		exit;
	}

	$resultado = $cirugiaModel->agregarCirugia($nombre, $gravedad, $estado);
	echo json_encode(["status" => $resultado ? "success" : "error"]);
	exit;
}

if (isset($_POST["obtenerCirugia"], $_POST["id"])) {
	$resultado = $cirugiaModel->obtenerCirugiaPorId((int) $_POST["id"]);
	echo json_encode([
		"status" => $resultado ? "success" : "error",
		"resultado" => $resultado ?: []
	]);
	exit;
}

if (isset($_POST["actualizar"], $_POST["id"])) {
	verificarRol([1]);
	
	$nombre = trim((string) ($_POST["nombre_cirugia"] ?? ""));
	$gravedad = trim((string) ($_POST["gravedad"] ?? ""));
	$estado = (int) $_POST["estado"];
	
	$cirugiaModel->clearErrors();
	
	$cirugiaModel->validateRequired(['nombre' => $nombre, 'gravedad' => $gravedad, 'estado' => $estado], ['nombre', 'gravedad', 'estado']);
	
	$cirugiaModel->validateMinLength($nombre, 'nombre', 5);
	$cirugiaModel->validateMaxLength($nombre, 'nombre', 50);
	$cirugiaModel->validateNombreCirugia($nombre);
	
	$cirugiaModel->validateInArray($gravedad, 'gravedad', ['Leve', 'Moderada', 'Grave']);
	if ($estado !== 0 && $estado !== 1) {
		$cirugiaModel->addError('estado', 'Debe ser activo o inactivo.');
	}

	$errors = $cirugiaModel->getErrors();
	if(!empty($cirugiaModel->getErrors())) {
		echo json_encode([
			"status" => "error",
			"errors" => $errors
		]);
		exit;
	}
	
	$resultado = $cirugiaModel->actualizarCirugia((int) $_POST["id"], $nombre, $gravedad, $estado);
		

	echo json_encode(["status" => $resultado ? "success" : "error"]);
	exit;
}

if (isset($_POST["eliminar"], $_POST["id"])) {
	verificarRol([1]);

	$resultado = $cirugiaModel->eliminarCirugia((int) $_POST["id"]);
	echo json_encode(["status" => $resultado ? "success" : "error"]);
	exit;
}

require_once "app/view/CirugiaView.php";
