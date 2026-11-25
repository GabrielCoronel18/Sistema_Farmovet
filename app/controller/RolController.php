<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\RolModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1]);

$rolModel = new RolModel();

if (isset($_POST['obtener'])) {
    $draw = (int) ($_POST['draw'] ?? 1);
    $start = max(0, (int) ($_POST['start'] ?? 0));
    $limite = (int) ($_POST['length'] ?? 10);
    $limite = in_array($limite, [5, 10, 20], true) ? $limite : 10;
    $pagina = (int) floor($start / $limite) + 1;
    $busqueda = trim((string) ($_POST['search']['value'] ?? $_POST['parametro'] ?? ''));

    if ($busqueda !== '') {
        $roles = $rolModel->filtrarRoles($busqueda, $pagina, $limite);
    } else {
        $roles = $rolModel->obtenerRoles($pagina, $limite);
    }

    if (isset($_POST['draw'])) {
        $recordsTotal = $rolModel->contarRoles();
        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $busqueda !== '' ? $rolModel->contarRoles($busqueda) : $recordsTotal,
            "data" => $roles
        ]);
        exit;
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
     
    $rolModel->clearErrors();
    $rolModel->validateRequired(['nombre' => $nombre],['nombre']);
    if ($nombre !== '') {
        $rolModel->validateMinLength($nombre, 'nombre', 3);
        $rolModel->validateMaxLength($nombre, 'nombre', 30);
        $rolModel->validateNombreRol($nombre);
    }

    $errors = $rolModel->getErrors();
    if (!empty($errors)) {
        echo json_encode([
            "status" => "error",
            "mensaje" => reset($errors),
            "errors" => $errors
        ]);
        exit;
    }


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

    $rolModel->clearErrors();
    $rolModel->validateRequired(['nombre' => $nombre],['nombre']);
    if ($nombre !== '') {
        $rolModel->validateMinLength($nombre, 'nombre', 3);
        $rolModel->validateMaxLength($nombre, 'nombre', 30);
        $rolModel->validateNombreRol($nombre);
    }

    $errors = $rolModel->getErrors();
    if (!empty($errors)) {
        echo json_encode([
            "status" => "error",
            "mensaje" => reset($errors),
            "errors" => $errors
        ]);
        exit;
    }

    if ($id <= 0) {
        echo json_encode(["status" => "error", "mensaje" => "El rol seleccionado no es válido."]);
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