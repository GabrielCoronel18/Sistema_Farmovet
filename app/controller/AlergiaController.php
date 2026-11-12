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
                echo json_encode($this->modelo->consultarAlergias()); 
                exit;

            case 'guardar':
                header('Content-Type: application/json');
                $id = $_POST['id_alergia'] ?? null;
                $nombre = trim($_POST['nombre_alergia'] ?? '');
                $tipo = trim($_POST['tipo_alergia'] ?? '');

                // 1. Validación de campos vacíos
                if (empty($nombre) || empty($tipo)) {
                    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                    exit;
                }

                // 2. Validación de longitud mínima y máxima
                if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 50) {
                    echo json_encode(['status' => 'error', 'message' => 'El nombre de la alergia debe tener entre 3 y 50 caracteres.']);
                    exit;
                }

                if (mb_strlen($tipo) < 3 || mb_strlen($tipo) > 50) {
                    echo json_encode(['status' => 'error', 'message' => 'El tipo de alergia debe tener entre 3 y 50 caracteres.']);
                    exit;
                }

                // 3. Validación de caracteres permitidos (Letras, números, espacios y acentos)
                if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s,\.-]+$/u', $nombre)) {
                    echo json_encode(['status' => 'error', 'message' => 'El nombre de la alergia contiene caracteres no permitidos.']);
                    exit;
                }

                if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s,\.-]+$/u', $tipo)) {
                    echo json_encode(['status' => 'error', 'message' => 'El tipo de alergia contiene caracteres no permitidos.']);
                    exit;
                }

                // Si pasa las validaciones, procedemos a guardar o actualizar
                if (!empty($id)) {
                    // Validar que el ID sea numérico por seguridad
                    if (!is_numeric($id)) {
                        echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
                        exit;
                    }
                    $resultado = $this->modelo->actualizarAlergia($id, $nombre, $tipo);
                    $mensaje = "Alergia actualizada correctamente.";
                } else {
                    $resultado = $this->modelo->registrarAlergia($nombre, $tipo);
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
                if ($id && is_numeric($id) && $this->modelo->eliminarAlergia($id)) {
                    echo json_encode(['status' => 'success', 'message' => 'Alergia eliminada correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la alergia.']);
                }
                exit;

            default:
                require_once "app/view/AlergiaView.php";
                break;
        }
    }
}