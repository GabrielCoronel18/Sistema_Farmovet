<?php

use Gabriel\SistemaFarmovet\model\EspeciesModel;
use Gabriel\SistemaFarmovet\model\RazasModel;

$raza = new RazasModel();
$especiesModel = new EspeciesModel();

// Se llama a obtenerEspecie() enviando (página, limite)
$especies = $especiesModel->obtenerEspecie(1, 500);

$mensajeAlerta = "";

// 1. AGREGAR RAZA
if(isset($_POST["agregar"])){
    $nombre_raza = trim($_POST["nombre_raza"] ?? $_POST["nombre"] ?? "");
    $id_especie = trim($_POST["id_especie"] ?? $_POST["especie"] ?? "");

    if (!empty($nombre_raza) && !empty($id_especie)) {
        if (count($raza->validarRaza($nombre_raza, $id_especie)) == 0) {
            if($raza->registrar($nombre_raza, $id_especie)){
                echo json_encode(["status"=>"success"]);
            } else {
                echo json_encode(["status"=>"error", "message" => "Error al guardar en la base de datos"]);
            }
        } else {
            echo json_encode(["status"=>"error", "message" => "La raza ya se encuentra registrada para esta especie"]);
        }
    } else {
        echo json_encode(["status"=>"error", "message" => "El nombre de la raza y la especie son obligatorios"]);
    }
    exit;
}

// 2. ACTUALIZAR RAZA
if(isset($_POST["actualizar"])){
    $id_raza = trim($_POST["id_raza"] ?? $_POST["id"] ?? "");
    $nombre_raza = trim($_POST["nombre_raza"] ?? $_POST["nombre"] ?? "");
    $id_especie = trim($_POST["id_especie"] ?? $_POST["especie"] ?? "");

    if(!empty($id_raza) && !empty($nombre_raza) && !empty($id_especie)){
        if($raza->modificarRaza($id_raza, $nombre_raza, $id_especie)){
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error", "message" => "No se pudo actualizar la raza"]);
        }
    } else {
        echo json_encode(["status"=>"error", "message" => "Faltan datos obligatorios (ID, Nombre o Especie)"]);
    }
    exit;
}

// 3. ELIMINAR RAZA (ELIMINACIÓN LÓGICA)
if(isset($_POST["eliminar"])){
    $id_raza = trim($_POST["id_raza"] ?? $_POST["id"] ?? "");

    if(!empty($id_raza)){
        if($raza->eliminarRaza($id_raza)){
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error", "message" => "No se pudo eliminar el registro"]);
        }
    } else {
        echo json_encode(["status"=>"error", "message" => "ID de raza no recibido"]);
    }
    exit;
}

// 4. OBTENER RAZA POR ID (Para cargar en el modal de actualización)
if(isset($_POST["obtenerRaza"]) || isset($_POST["obtenerRazaPorId"])){
    $id_raza = trim($_POST["id_raza"] ?? $_POST["id"] ?? "");

    $resultado = $raza->obtenerRazaPorId($id_raza);

    if($resultado){
        echo json_encode(["status"=>"success", "resultado" => $resultado]);
    } else {
        echo json_encode(["status"=>"error", "resultado" => []]);
    }
    exit;
}

// 5. OBTENER TODAS LAS RAZAS / FILTRAR (Para DataTables)
if(isset($_POST["obtener"])){
    $pagina = $_POST["pagina"] ?? 1;
    $limitacion = $_POST["limite"] ?? 500;
    
    if(isset($_POST["parametro"]) && !empty($_POST["parametro"])){
        $param = $_POST["parametro"];
        $resultados = $raza->filtrarRaza($param, $pagina, $limitacion);
    } else {
        $resultados = $raza->obtenerRaza($pagina, $limitacion);
    }

    if($resultados){
        echo json_encode(["status"=>"success", "resultados" => $resultados]);
    } else {
        echo json_encode(["status"=>"error", "resultados" => []]);
    }
    exit;
}

include_once "app/view/componente/RazasView.php";
?>