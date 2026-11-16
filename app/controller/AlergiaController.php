<?php
namespace Gabriel\SistemaFarmovet\controller;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

use Gabriel\SistemaFarmovet\model\AlergiaModel;

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
