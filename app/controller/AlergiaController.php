<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\AlergiaModel;

class AlergiaController {

    private $modelo;

    public function __construct() {
        $this->modelo = new AlergiaModel();
    }

    public function index() {
        $accion = $_GET['accion'] ?? 'vista';

        switch ($accion) {
            case 'listar':
                header('Content-Type: application/json');
                echo json_encode($this->modelo->obtenerTodas());
                exit;

            case 'guardar':
                header('Content-Type: application/json');
                $id = $_POST['id_alergia'] ?? null;
                $nombre = trim($_POST['nombre_alergia'] ?? '');
                $tipo = trim($_POST['tipo_alergia'] ?? '');

                if (empty($nombre) || empty($tipo)) {
                    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                    exit;
                }

                if (!empty($id)) {
                    $resultado = $this->modelo->actualizar($id, $nombre, $tipo);
                    $mensaje = "Alergia actualizada correctamente.";
                } else {
                    $resultado = $this->modelo->guardar($nombre, $tipo);
                    $mensaje = "Alergia registrada correctamente.";
                }

                if ($resultado) {
                    echo json_encode(['status' => 'success', 'message' => $mensaje]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al guardar en la base de datos.']);
                }
                exit;

            case 'eliminar':
                header('Content-Type: application/json');
                $id = $_POST['id_alergia'] ?? null;
                if ($id && $this->modelo->eliminar($id)) {
                    echo json_encode(['status' => 'success', 'message' => 'Alergia eliminada correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la alergia.']);
                }
                exit;

            default:
    require_once __DIR__ . "/../view/AlergiaView.php";
    break;
        }
    }
}