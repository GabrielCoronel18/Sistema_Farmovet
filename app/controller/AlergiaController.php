<?php
namespace Gabriel\SistemaFarmovet\controller;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

use Gabriel\SistemaFarmovet\model\AlergiaModel;

verificarRol([1, 2]);
//class AlergiaController {

    //private $modelo;

    //public function __construct() {
        $modelo = new AlergiaModel();
    //}

    //public function index() {
        $accion = $_GET['accion'] ?? 'vista';

        switch ($accion) {
            
            case 'listar':
                verificarRol([1, 2]);
                header('Content-Type: application/json');
                echo json_encode($modelo->registrarAlergia());
                exit;

            case 'guardar':
                verificarRol([1]);
                header('Content-Type: application/json');
                $id = $_POST['id_alergia'] ?? null;
                $nombre = trim($_POST['nombre_alergia'] ?? '');
                $tipo = trim($_POST['tipo_alergia'] ?? '');

                if (empty($nombre) || empty($tipo)) {
                    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                    exit;
                }

                if (!empty($id)) {
                    $resultado = $modelo->actualizarAlergia($id, $nombre, $tipo);
                    $mensaje = "Alergia actualizada correctamente.";
                } else {
                    $resultado = $modelo->registrarAlergia($nombre, $tipo);
                    $mensaje = "Alergia registrada correctamente.";
                }

                if ($resultado) {
                    echo json_encode(['status' => 'success', 'message' => $mensaje]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al guardar en la base de datos.']);
                }
                exit;

            case 'eliminar':
                verificarRol([1]);
                header('Content-Type: application/json');
                $id = $_POST['id_alergia'] ?? null;
                if ($id && $modelo->eliminarAlergia($id)) {
                    echo json_encode(['status' => 'success', 'message' => 'Alergia eliminada correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la alergia.']);
                }
                exit;

            default:
    require_once "app/view/AlergiaView.php";
    break;
        }
    //}
//}