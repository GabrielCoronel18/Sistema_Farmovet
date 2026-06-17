<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\RolModel;

class RolController
{
    private RolModel $rolModel;

    public function __construct()
    {
        $this->rolModel = new RolModel();
    }

    public function index()
    {
        $pagina = (int)($_GET['pagina'] ?? 1);
        $limite = 10;
        $busqueda = $_GET['buscar'] ?? '';

        if ($busqueda) {
            $roles = $this->rolModel->filtrarRoles($busqueda, $pagina, $limite);
        } else {
            $roles = $this->rolModel->obtenerRoles($pagina, $limite);
        }

        $data = [
            'roles'    => $roles,
            'pagina'   => $pagina,
            'busqueda' => $busqueda,
        ];
        extract($data);
        require_once __DIR__ . '/../view/RolView.php';
    }

    public function crear()
    {
        $accion = 'crear';
        $rol = null;
        require_once __DIR__ . '/../view/NuevoRolView.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=Rol');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        if (empty($nombre)) {
            header('Location: ?url=Rol&accion=crear&error=1');
            exit;
        }

        $exito = $this->rolModel->agregarRol($nombre);
        if ($exito) {
            header('Location: ?url=Rol&mensaje=creado');
        } else {
            header('Location: ?url=Rol&accion=crear&error=bd');
        }
        exit;
    }

    public function editar()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?url=Rol');
            exit;
        }

        $rol = $this->rolModel->obtenerRolPorId($id);
        if (!$rol) {
            header('Location: ?url=Rol');
            exit;
        }

        $accion = 'editar';
        require_once __DIR__ . '/../view/NuevoRolView.php';
    }

    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=Rol');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?url=Rol');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        if (empty($nombre)) {
            header('Location: ?url=Rol&accion=editar&id=' . $id . '&error=1');
            exit;
        }

        $exito = $this->rolModel->actualizarRol($id, $nombre);
        if ($exito) {
            header('Location: ?url=Rol&mensaje=actualizado');
        } else {
            header('Location: ?url=Rol&accion=editar&id=' . $id . '&error=bd');
        }
        exit;
    }

    public function eliminar()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->rolModel->eliminarRol($id);
        }
        header('Location: ?url=Rol&mensaje=eliminado');
        exit;
    }
}

// Auto-ejecución para el FrontController
$accion = $_GET['accion'] ?? 'index';
$controlador = new RolController();
if (method_exists($controlador, $accion)) {
    $controlador->$accion();
} else {
    http_response_code(404);
    echo "Error";
}