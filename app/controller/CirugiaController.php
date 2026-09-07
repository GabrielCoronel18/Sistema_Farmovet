<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\CirugiaModel;

$cirugiaModel = new CirugiaModel();

if (isset($_POST["obtener"])) {
	$pagina = max(1, (int) ($_POST["pagina"] ?? 1));
	$limitacion = max(1, (int) ($_POST["limite"] ?? 5));
	$resultados = isset($_POST["parametro"])
		? $cirugiaModel->filtrarCirugia((string) $_POST["parametro"], $pagina, $limitacion)
		: $cirugiaModel->obtenerCirugias($pagina, $limitacion);

	echo json_encode([
		"status" => $resultados ? "success" : "error",
		"resultados" => $resultados ?: []
	]);
	exit;
}

if (isset($_POST["agregar"])) {
	$nombre = trim((string) ($_POST["nombre_cirugia"] ?? ""));
	$gravedad = trim((string) ($_POST["gravedad"] ?? ""));
	$resultado = $nombre !== "" && $gravedad !== ""
		? $cirugiaModel->agregarCirugia($nombre, $gravedad)
		: false;

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
	$nombre = trim((string) ($_POST["nombre_cirugia"] ?? ""));
	$gravedad = trim((string) ($_POST["gravedad"] ?? ""));
	$resultado = $nombre !== "" && $gravedad !== ""
		? $cirugiaModel->actualizarCirugia((int) $_POST["id"], $nombre, $gravedad)
		: false;

	echo json_encode(["status" => $resultado ? "success" : "error"]);
	exit;
}

if (isset($_POST["eliminar"], $_POST["id"])) {
	$resultado = $cirugiaModel->eliminarCirugia((int) $_POST["id"]);
	echo json_encode(["status" => $resultado ? "success" : "error"]);
	exit;
}

require_once "app/view/CirugiaView.php";
