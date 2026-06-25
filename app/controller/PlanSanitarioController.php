<?php
namespace Gabriel\SistemaFarmovet\controller;
use Gabriel\SistemaFarmovet\model\PlanSanitarioModel;

// 1. Estructura de clase obligatoria para que el FrontController no te mande al Login
class PlanSanitarioController {
    public function __construct() {
        // Ejecuta la lógica lineal nativa de tu sistema al ser instanciado
        global $planModel;
    }
}

// 2. Lógica funcional e idéntica a tu módulo de Roles (Peticiones e Inclusiones)
$planModel = new PlanSanitarioModel();

if (isset($_POST['obtener'])) {
    $pagina = (int)($_POST['pagina'] ?? 1);
    $limite = 10;
    $busqueda = $_POST['parametro'] ?? '';

    if ($busqueda !== '') {
        $planes = $planModel->filtrarPlanes($busqueda, $pagina, $limite);
    } else {
        $planes = $planModel->obtenerPlanes($pagina, $limite);
    }

    echo json_encode([
        "status" => "success",
        "resultados" => $planes,
        "pagina" => $pagina
    ]);
    exit;
}

if (isset($_POST['obtenerPlan']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $plan = $planModel->obtenerPlanPorId($id);
    if ($plan) {
        echo json_encode(["status" => "success", "resultado" => $plan]);
    } else {
        echo json_encode(["status" => "error", "resultado" => null]);
    }
    exit;
}

if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = trim($_POST['fecha_inicio'] ?? '');

    if (empty($nombre) || empty($descripcion) || empty($fecha_inicio)) {
        echo json_encode(["status" => "error", "mensaje" => "Todos los campos son obligatorios."]);
        exit;
    }
    $exito = $planModel->agregarPlan($nombre, $descripcion, $fecha_inicio);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

if (isset($_POST['actualizar'])) {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = trim($_POST['fecha_inicio'] ?? '');

    if ($id <= 0 || empty($nombre) || empty($descripcion) || empty($fecha_inicio)) {
        echo json_encode(["status" => "error", "mensaje" => "Complete los campos."]);
        exit;
    }
    $exito = $planModel->actualizarPlan($id, $nombre, $descripcion, $fecha_inicio);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $exito = $planModel->eliminarPlan($id);
    echo json_encode(["status" => $exito ? "success" : "error"]);
    exit;
}

// Renderizado de las vistas originales de tu sistema
$accion = $_GET['accion'] ?? 'index';
if ($accion === 'crear' || $accion === 'editar') {
    require_once __DIR__ . '/../view/NuevoPlanSanitarioView.php';
} else {
    require_once __DIR__ . '/../view/Plan_sanitarioView.php';
}