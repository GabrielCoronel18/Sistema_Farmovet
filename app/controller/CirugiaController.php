<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\CirugiaModel;

use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1, 2, 3]);

$cirugiaModel = new CirugiaModel();

$errors = [];

if (isset($_POST["obtener"])) {
	
	verificarRol([1, 2, 3]);

	$pagina = max(1, (int) ($_POST["pagina"] ?? 1));
	$limitacion = (int) ($_POST["limite"] ?? 5);
	$limitacion = in_array($limitacion, [5, 10, 20], true) ? $limitacion : 5;
	$resultados = isset($_POST["parametro"])
		? $cirugiaModel->filtrarCirugia((string) $_POST["parametro"], $pagina, $limitacion)
		: $cirugiaModel->obtenerCirugias($pagina, $limitacion);
	$total = $cirugiaModel->contarCirugias(isset($_POST["parametro"]) ? (string) $_POST["parametro"] : null);

	echo json_encode([
		"status" => "success",
		"resultados" => $resultados,
		"hasMore" => $pagina * $limitacion < $total
	]);
	exit;
}

if (isset($_POST["agregar"])) {
	verificarRol([1, 2]);

	$nombre = trim((string) ($_POST["nombre_cirugia"] ?? ""));
	$gravedad = trim((string) ($_POST["gravedad"] ?? ""));
	$estado = (int) $_POST["estado"];
	
	$cirugiaModel->clearErrors();
	
	$cirugiaModel->validateRequired(['nombre' => $nombre, 'gravedad' => $gravedad, 'estado' => $estado], ['nombre', 'gravedad', 'estado']);
	
	$cirugiaModel->validateMinLength($nombre, 'nombre', 5);
	$cirugiaModel->validateMaxLength($nombre, 'nombre', 50);

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
	verificarRol([1, 2]);
	
	$nombre = trim((string) ($_POST["nombre_cirugia"] ?? ""));
	$gravedad = trim((string) ($_POST["gravedad"] ?? ""));
	$estado = (int) $_POST["estado"];
	
	$cirugiaModel->clearErrors();
	
	$cirugiaModel->validateRequired(['nombre' => $nombre, 'gravedad' => $gravedad, 'estado' => $estado], ['nombre', 'gravedad', 'estado']);
	
	$cirugiaModel->validateMinLength($nombre, 'nombre', 5);
	$cirugiaModel->validateMaxLength($nombre, 'nombre', 50);
	
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
	verificarRol([1, 2]);

	$resultado = $cirugiaModel->eliminarCirugia((int) $_POST["id"]);
	echo json_encode(["status" => $resultado ? "success" : "error"]);
	exit;
}

require_once "app/view/CirugiaView.php";
