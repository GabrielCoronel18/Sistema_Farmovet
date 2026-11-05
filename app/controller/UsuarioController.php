<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\UsuarioModel;
use Gabriel\SistemaFarmovet\model\RolModel;
use Gabriel\SistemaFarmovet\helpers\traits\Validation;
use Gabriel\SistemaFarmovet\helpers\traits\UsuarioValidation;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

verificarRol([1]);

$usuarioModel = new UsuarioModel();
$rolModel = new RolModel();
$validador = new class {
    use Validation;
    use UsuarioValidation;
};

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
    $cedula = strtoupper(trim($_POST['cedula'] ?? ''));
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';
    $rol = (int)($_POST['rol'] ?? 0);

    $validador->clearErrors();
    $validador->validateRequired(
        compact('cedula', 'nombre', 'apellido', 'correo'),
        ['cedula', 'nombre', 'apellido', 'correo']
    );
    $validador->validateMinLength($nombre, 'nombre', 2);
    $validador->validateMaxLength($nombre, 'nombre', 30);
    $validador->validateNombreUsuario($nombre, 'nombre');
    $validador->validateMinLength($apellido, 'apellido', 2);
    $validador->validateMaxLength($apellido, 'apellido', 30);
    $validador->validateNombreUsuario($apellido, 'apellido');
    $validador->validateCedulaUsuario($cedula);
    $validador->validateTelefonoUsuario($telefono);
    $validador->validateCorreoUsuario($correo);
    $validador->validateContraseñaUsuario($contraseña, true);
    if ($rol <= 0) {
        $validador->addError('rol', 'Seleccione un rol válido.');
    }

    if (!empty($validador->getErrors())) {
        echo json_encode(["status" => "error", "errors" => $validador->getErrors()]);
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
        echo json_encode(["status" => "error", "mensaje" => "No se encontro la cedula original"]);
        exit;
    }

    $cedula = strtoupper(trim($_POST['cedula'] ?? ''));
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $rol = (int)($_POST['rol'] ?? 0);
    $contraseña = $_POST['contraseña'] ?? '';

    $validador->clearErrors();
    $validador->validateRequired(
        compact('cedula', 'nombre', 'apellido', 'correo'),
        ['cedula', 'nombre', 'apellido', 'correo']
    );
    $validador->validateMinLength($nombre, 'nombre', 2);
    $validador->validateMaxLength($nombre, 'nombre', 30);
    $validador->validateNombreUsuario($nombre, 'nombre');
    $validador->validateMinLength($apellido, 'apellido', 2);
    $validador->validateMaxLength($apellido, 'apellido', 30);
    $validador->validateNombreUsuario($apellido, 'apellido');
    $validador->validateCedulaUsuario($cedula);
    $validador->validateTelefonoUsuario($telefono);
    $validador->validateCorreoUsuario($correo);
    $validador->validateContraseñaUsuario($contraseña, false);
    if ($rol <= 0) {
        $validador->addError('rol', 'Seleccione un rol válido.');
    }

    if (!empty($validador->getErrors())) {
        echo json_encode(["status" => "error", "errors" => $validador->getErrors()]);
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