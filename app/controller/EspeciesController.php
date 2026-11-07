<?php

use Gabriel\SistemaFarmovet\model\EspeciesModel;

$especies = new EspeciesModel();

$mensajeAlerta = "";

// 1. AGREGAR
if (isset($_POST["agregar"])) {
    $nombre = trim($_POST["nombre_especie"] ?? $_POST["nombre"] ?? "");

    if (count($especies->validarEspecies($nombre)) == 0) {
        if ($especies->registrar($nombre)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "La especie ya se encuentra registrada"]);
    }
    exit;
}

// 2. ACTUALIZAR
if (isset($_POST["actualizar"])) {
    $id = trim($_POST["id_especie"] ?? $_POST["id"] ?? "");
    $nombre = trim($_POST["nombre_especie"] ?? $_POST["nombre"] ?? "");

    if ($especies->modificarEspecie($id, $nombre)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

// 3. ELIMINAR
if (isset($_POST["eliminar"])) {
    $id = trim($_POST["id_especie"] ?? $_POST["id"] ?? "");

    if ($especies->eliminarEspecie($id)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit;
}

// 4. OBTENER UNA ESPECIE POR ID
if (isset($_POST["obtenerEspecie"])) {
    $id = trim($_POST["id_especie"] ?? $_POST["id"] ?? "");

    $resultado = $especies->obtenerEspeciePorId($id);

    if ($resultado) {
        echo json_encode(["status" => "success", "resultado" => $resultado]);
    } else {
        echo json_encode(["status" => "error", "resultado" => []]);
    }
    exit;
}

// 5. OBTENER TODAS / FILTRAR
if (isset($_POST["obtener"])) {
    $pagina = $_POST["pagina"] ?? 1;
    $limitacion = $_POST["limite"] ?? 500;

    if (isset($_POST["parametro"]) && !empty($_POST["parametro"])) {
        $param = $_POST["parametro"];
        $resultados = $especies->filtrarEspecie($param, $pagina, $limitacion);
    } else {
        $resultados = $especies->obtenerEspecie($pagina, $limitacion);
    }

    if ($resultados) {
        echo json_encode(["status" => "success", "resultados" => $resultados]);
    } else {
        echo json_encode(["status" => "error", "resultados" => []]);
    }
    exit;
}

// 👇 ESTO ES LO NUEVO: solo carga la vista si NO es una petición AJAX
// (es decir, si no viene ninguna acción por POST)
include_once "app/view/componente/EspeciesView.php";