<?php
namespace Gabriel\SistemaFarmovet\controller;

use Gabriel\SistemaFarmovet\model\AlergiaModel;

<<<<<<< HEAD
//class AlergiaController {
require_once "app/view/AlergiaView.php";
$modelo = new AlergiaModel();

    //public function index() {
        $accion = $_POST['accion'] ?? 'listar';

        switch ($accion) {
            case 'listar':
                header('Content-Type: application/json');
                echo json_encode($modelo->consultarAlergias()); 
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

                break;
        }
        
    //}
//}
=======
verificarRol([1, 2]);

        
     $modelo = new AlergiaModel();
    
     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consultar'])) {
        
        $resultados = $modelo->obtenerAlergias();
        header('Content-Type: application/json');
        echo json_encode([
        "status" => "success",
        "resultados" => $resultados,
                                    ]);
        
        exit;
     }
     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consultarPorId'])) {
        $id = $_POST['id'] ?? null;
        $resultado = $modelo->obtenerAlergiaPorId($id);
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $resultado ? "success" : "error",
            "resultado" => $resultado,
        ]);
        exit;
     }
     
     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
        verificarRol([1]);

        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $tipo = trim((string) ($_POST['tipo'] ?? ''));
       

        $modelo->clearErrors();
        
        $modelo->validateRequired(['nombre' => $nombre, 'tipo' => $tipo], ['nombre', 'tipo']);
        if ($nombre !== '') {
            $modelo->validateMinLength($nombre, 'nombre', 3);
            $modelo->validateMaxLength($nombre, 'nombre', 80);
            $modelo->validateNombreAlergia($nombre);
        }
        if ($tipo !== '') {
            $modelo->validateMinLength($tipo, 'tipo', 3);
            $modelo->validateMaxLength($tipo, 'tipo', 80);
            $modelo->validateTipoAlergia($tipo);
        }

        $errors = $modelo->getErrors();
        if(!empty($errors)) {
            echo json_encode([
                "status" => "error",
                "errors" => $errors,
                "message" => "Existen errores en los datos proporcionados."
            ]);
            exit;
        }
        
        $resultado = $modelo->agregarAlergia($nombre, $tipo);

        header('Content-Type: application/json');
        echo json_encode([
            "status" => $resultado ? "success" : "error",
            "message" => $resultado ? "Alergia registrada correctamente." : "Error al registrar la alergia.",
        ]);
        exit;
     }
     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
        verificarRol([1]);
        $id = (int) ($_POST['id_alergia'] ?? 0);
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $tipo = trim((string) ($_POST['tipo'] ?? ''));
        
        $modelo->clearErrors();

        $modelo->validateRequired(['nombre' => $nombre, 'tipo' => $tipo], ['nombre', 'tipo']);
        if ($nombre !== '') {
            $modelo->validateMinLength($nombre, 'nombre', 3);
            $modelo->validateMaxLength($nombre, 'nombre', 80);
            $modelo->validateNombreAlergia($nombre);
        }
        if ($tipo !== '') {
            $modelo->validateMinLength($tipo, 'tipo', 3);
            $modelo->validateMaxLength($tipo, 'tipo', 80);
            $modelo->validateTipoAlergia($tipo);
        }

        $errors = $modelo->getErrors();
        if (!empty($errors)) {
            echo json_encode([
                "status" => "error",
                "errors" => $errors,
                "message" => "Existen errores en los datos proporcionados."
            ]);
            exit;
        }

        if ($id <= 0) {
            echo json_encode([
                "status" => "error",
                "message" => "La alergia seleccionada no es válida."
            ]);
            exit;
        }

        $resultado = $modelo->actualizarAlergia($id, $nombre, $tipo);
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $resultado ? "success" : "error",
            "message" => $resultado ? "Alergia editada correctamente." : "Error al editar la alergia.",
        ]);
        exit;
     }

     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
        verificarRol([1]);

        $id = $_POST['id'];
        $resultado = $modelo->eliminarAlergia($id);
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $resultado ? "success" : "error",
            "message" => $resultado ? "Alergia eliminada correctamente." : "Error al eliminar la alergia.",
        ]);
        exit;
     }

    require_once "app/view/AlergiaView.php";
>>>>>>> 83dbc3f81f4e05da9989bd538b7de88c1c31c85f
