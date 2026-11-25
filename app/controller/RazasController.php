<?php

use Gabriel\SistemaFarmovet\model\EspeciesModel;
use Gabriel\SistemaFarmovet\model\RazasModel;
use function Gabriel\SistemaFarmovet\helpers\verificarRol;

$raza = new RazasModel();
$especiesModel = new EspeciesModel();

// Se llama a obtenerEspecie() enviando (página, limite) para el <select> del modal
$especies = $especiesModel->obtenerEspecie(1, 500);

$mensajeAlerta = "";

// 1. AGREGAR
if (isset($_POST["agregar"])) {

    verificarRol([1]);
    $nombre_raza = trim($_POST["nombre_raza"] ?? $_POST["nombre"] ?? "");
    $id_especie = trim($_POST["id_especie"] ?? $_POST["especie"] ?? "");

    $raza->clearErrors();
    $raza->validateRequired(['nombre_raza' => $nombre_raza, 'id_especie' => $id_especie], ['nombre_raza', 'id_especie']);
    
    if ($nombre_raza !== '') {
        $raza->validateMinLength($nombre_raza, 'nombre_raza', 2);
        $raza->validateMaxLength($nombre_raza, 'nombre_raza', 50);
        $raza->validateNombreRaza($nombre_raza);
    }
    
    $errores = $raza->getErrors();
    if (!empty($errores)) {
        echo json_encode([
            "status" => "error",
            "message" => reset($errores),
            "errors" => $errores
        ]);
        exit;
    }

    if (count($raza->validarRaza($nombre_raza, $id_especie)) == 0) {
        if ($raza->registrar($nombre_raza, $id_especie)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al guardar en la base de datos"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "La raza ya se encuentra registrada para esta especie"]);
    }
    exit;
}

// 2. ACTUALIZAR
if (isset($_POST["actualizar"])) {
    verificarRol([1]);
    $id_raza = trim($_POST["id_raza"] ?? $_POST["id"] ?? "");
    $nombre_raza = trim($_POST["nombre_raza"] ?? $_POST["nombre"] ?? "");
    $id_especie = trim($_POST["id_especie"] ?? $_POST["especie"] ?? "");

    $raza->clearErrors();
    $raza->validateRequired(['nombre_raza' => $nombre_raza, 'id_especie' => $id_especie], ['nombre_raza', 'id_especie']);
    
    if ($nombre_raza !== '') {
        $raza->validateMinLength($nombre_raza, 'nombre_raza', 2);
        $raza->validateMaxLength($nombre_raza, 'nombre_raza', 50);
        $raza->validateNombreRaza($nombre_raza);
    }
    
    $errores = $raza->getErrors();
    if (!empty($errores)) {
        echo json_encode([
            "status" => "error",
            "message" => reset($errores),
            "errors" => $errores
        ]);
        exit;
    }

    if ($raza->modificarRaza($id_raza, $nombre_raza, $id_especie)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar la raza"]);
    }
    exit;
}

// 3. ELIMINAR
if (isset($_POST["eliminar"])) {
    verificarRol([1]);
    $id_raza = trim($_POST["id_raza"] ?? $_POST["id"] ?? "");

    if ($raza->eliminarRaza($id_raza)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo eliminar el registro"]);
    }
    exit;
}

// 4. OBTENER UNA RAZA POR ID
if (isset($_POST["obtenerRaza"]) || isset($_POST["obtenerRazaPorId"])) {
    $id_raza = trim($_POST["id_raza"] ?? $_POST["id"] ?? "");

    $resultado = $raza->obtenerRazaPorId($id_raza);

    if ($resultado) {
        echo json_encode(["status" => "success", "resultado" => $resultado]);
    } else {
        echo json_encode(["status" => "error", "resultado" => []]);
    }
    exit;
}

// 5. OBTENER TODAS / FILTRAR
if (isset($_POST["obtener"])) {
    verificarRol([1, 2]);
    $pagina = $_POST["pagina"] ?? 1;
    $limitacion = $_POST["limite"] ?? 500;

    if (isset($_POST["parametro"]) && !empty($_POST["parametro"])) {
        $param = $_POST["parametro"];
        $resultados = $raza->filtrarRaza($param, $pagina, $limitacion);
    } else {
        $resultados = $raza->obtenerRaza($pagina, $limitacion);
    }

    if ($resultados) {
        echo json_encode(["status" => "success", "resultados" => $resultados]);
    } else {
        echo json_encode(["status" => "error", "resultados" => []]);
    }
    exit;
}

// Carga la vista si no es una petición AJAX
include_once "app/view/componente/RazasView.php";
?>