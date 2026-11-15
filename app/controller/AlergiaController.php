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
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $resultado = $modelo->agregarAlergia($nombre, $tipo);
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $resultado ? "success" : "error",
            "message" => $resultado ? "Alergia registrada correctamente." : "Error al registrar la alergia.",
        ]);
        exit;
     }
     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
        $id = $_POST['id_alergia'];
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $resultado = $modelo->actualizarAlergia($id, $nombre, $tipo);
        header('Content-Type: application/json');
        echo json_encode([
            "status" => $resultado ? "success" : "error",
            "message" => $resultado ? "Alergia editada correctamente." : "Error al editar la alergia.",
        ]);
        exit;
     }

     if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
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
