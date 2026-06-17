<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\UsuarioModel;
use Gabriel\SistemaFarmovet\model\RolModel;

class UsuarioController
{
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $limite = 5;
        $busqueda = $_GET['buscar'] ?? '';

        if ($busqueda) {
            $usuarios = $this->usuarioModel->filtrarUsuarios($busqueda, $pagina, $limite);
        } else {
            $usuarios = $this->usuarioModel->obtenerUsuarios($pagina, $limite);
        }

        $data = [
            'usuarios' => $usuarios,
            'pagina'   => $pagina,
            'busqueda' => $busqueda,
        ];
        extract($data);
        require_once __DIR__ . '/../view/UsuarioView.php';
    }

    public function crear()
    {
        $accion = 'crear';
        $usuario = null;
        $rolModel = new RolModel();
        $roles = $rolModel->obtenerRoles(1, 100);
        require_once __DIR__ . '/../view/NuevoUsuarioView.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=Usuario');
            exit;
        }

        $cedula = $_POST['cedula'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $contraseña = $_POST['contraseña'] ?? '';
        $rol = (int)($_POST['rol'] ?? 0);

        if (empty($cedula) || empty($nombre) || empty($apellido) || empty($correo) || empty($contraseña) || $rol <= 0) {
            header('Location: ?url=Usuario&accion=crear&error=1');
            exit;
        }

        if ($this->usuarioModel->existeCedula($cedula)) {
            header('Location: ?url=Usuario&accion=crear&error=duplicado&cedula=' . urlencode($cedula));
            exit;
        }

        $exito = $this->usuarioModel->agregarUsuario($cedula, $nombre, $apellido, $telefono, $correo, $contraseña, $rol);
        if ($exito) {
            header('Location: ?url=Usuario&mensaje=creado');
        } else {
            header('Location: ?url=Usuario&accion=crear&error=bd');
        }
        exit;
    }

    public function editar()
    {
        $cedula = $_GET['cedula'] ?? '';
        if (empty($cedula)) {
            header('Location: ?url=Usuario');
            exit;
        }

        $usuario = $this->usuarioModel->obtenerUsuarioPorCedula($cedula);
        if (!$usuario) {
            header('Location: ?url=Usuario');
            exit;
        }

        $accion = 'editar';
        $rolModel = new RolModel();
        $roles = $rolModel->obtenerRoles(1, 100);
        require_once __DIR__ . '/../view/NuevoUsuarioView.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=Usuario');
            exit;
        }

        $cedulaOriginal = $_POST['cedula_original'] ?? '';
        if (empty($cedulaOriginal)) {
            header('Location: ?url=Usuario');
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
            header('Location: ?url=Usuario&accion=editar&cedula=' . urlencode($cedulaOriginal) . '&error=1');
            exit;
        }

        $exito = $this->usuarioModel->actualizarUsuario(
            $cedulaOriginal,
            $cedula,
            $nombre,
            $apellido,
            $telefono,
            $correo,
            $contraseña ?: null,
            $rol
        );

        if ($exito) {
            header('Location: ?url=Usuario&mensaje=actualizado');
        } else {
            header('Location: ?url=Usuario&accion=editar&cedula=' . urlencode($cedulaOriginal) . '&error=bd');
        }
        exit;
    }

    public function eliminar()
    {
        $cedula = $_GET['cedula'] ?? '';
        if (!empty($cedula)) {
            $this->usuarioModel->eliminarUsuario($cedula);
        }
        header('Location: ?url=Usuario&mensaje=eliminado');
        exit;
    }
}

$accion = $_GET['accion'] ?? 'index';
$controlador = new UsuarioController();
if (method_exists($controlador, $accion)) {
    $controlador->$accion();
} else {
    http_response_code(404);
    echo "Error";
}