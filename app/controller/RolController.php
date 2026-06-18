<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\RolModel;

$rolModel = new RolModel();

if (isset($_POST['obtener'])) {
    $pagina = (int)($_POST['pagina'] ?? 1);
    $limite = 10;
    $busqueda = $_POST['parametro'] ?? '';

    if ($busqueda !== '') {
        $roles = $rolModel->filtrarRoles($busqueda, $pagina, $limite);
    } else {
        $roles = $rolModel->obtenerRoles($pagina, $limite);
    }

    echo json_encode([
        "status" => "success",
        "resultados" => $roles,
        "pagina" => $pagina
    ]);
    exit;
}

if (isset($_POST['obtenerRol']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $rol = $rolModel->obtenerRolPorId($id);
    if ($rol) {
        echo json_encode(["status" => "success", "resultado" => $rol]);
    } else {
        echo json_encode(["status" => "error", "resultado" => null]);
    }
    exit;
}

if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    if (empty($nombre)) {
        echo json_encode(["status" => "error", "mensaje" => "El nombre es obligatorio."]);
        exit;
    }
    $exito = $rolModel->agregarRol($nombre);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

if (isset($_POST['actualizar'])) {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    if ($id <= 0 || empty($nombre)) {
        echo json_encode(["status" => "error", "mensaje" => "Complete los campos."]);
        exit;
    }
    $exito = $rolModel->actualizarRol($id, $nombre);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $exito = $rolModel->eliminarRol($id);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

$accion = $_GET['accion'] ?? 'index';
if ($accion === 'crear' || $accion === 'editar') {
    require_once __DIR__ . '/../view/NuevoRolView.php';
} else {
    require_once __DIR__ . '/../view/RolView.php';
}