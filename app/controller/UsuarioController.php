<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\UsuarioModel;
use Gabriel\SistemaFarmovet\model\RolModel;

$usuarioModel = new UsuarioModel();
$rolModel = new RolModel();

if (isset($_POST['obtener'])) {
    $pagina = (int)($_POST['pagina'] ?? 1);
    $limite = 5;
    $busqueda = $_POST['parametro'] ?? '';

    if ($busqueda !== '') {
        $usuarios = $usuarioModel->filtrarUsuarios($busqueda, $pagina, $limite);
    } else {
        $usuarios = $usuarioModel->obtenerUsuarios($pagina, $limite);
    }

    echo json_encode([
        "status" => "success",
        "resultados" => $usuarios,
        "pagina" => $pagina
    ]);
    exit;
}

if (isset($_POST['obtenerUsuario']) && isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $usuario = $usuarioModel->obtenerUsuarioPorCedula($cedula);
    if ($usuario) {
        echo json_encode(["status" => "success", "resultado" => $usuario]);
    } else {
        echo json_encode(["status" => "error", "resultado" => null]);
    }
    exit;
}

if (isset($_POST['obtenerRoles'])) {
    $roles = $rolModel->obtenerRoles(1, 100);
    echo json_encode(["status" => "success", "resultados" => $roles]);
    exit;
}

if (isset($_POST['agregar'])) {
    $cedula = $_POST['cedula'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    $rol = (int)($_POST['rol'] ?? 0);

    if (empty($cedula) || empty($nombre) || empty($apellido) || empty($correo) || empty($contraseña) || $rol <= 0) {
        echo json_encode(["status" => "error", "mensaje" => "Complete todos los campos obligatorios."]);
        exit;
    }

    if ($usuarioModel->existeCedula($cedula)) {
        echo json_encode(["status" => "error", "mensaje" => "La cédula ya está registrada."]);
        exit;
    }

    $exito = $usuarioModel->agregarUsuario($cedula, $nombre, $apellido, $telefono, $correo, $contraseña, $rol);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

if (isset($_POST['actualizar'])) {
    $cedulaOriginal = $_POST['cedula_original'] ?? '';
    if (empty($cedulaOriginal)) {
        echo json_encode(["status" => "error", "mensaje" => "Cédula original no proporcionada."]);
        exit;
    }

    $cedula = $_POST['cedula'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $rol = (int)($_POST['rol'] ?? 0);
    $contraseña = $_POST['contraseña'] ?? '';

    if (empty($cedula) || empty($nombre) || empty($apellido) || empty($correo) || $rol <= 0) {
        echo json_encode(["status" => "error", "mensaje" => "Complete todos los campos obligatorios."]);
        exit;
    }

    $exito = $usuarioModel->actualizarUsuario(
        $cedulaOriginal,
        $cedula,
        $nombre,
        $apellido,
        $telefono,
        $correo,
        $contraseña ?: null,
        $rol
    );
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

if (isset($_POST['eliminar']) && isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $exito = $usuarioModel->eliminarUsuario($cedula);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

$accion = $_GET['accion'] ?? 'index';
if ($accion === 'crear' || $accion === 'editar') {
    require_once __DIR__ . '/../view/NuevoUsuarioView.php';
} else {
    require_once __DIR__ . '/../view/UsuarioView.php';
}