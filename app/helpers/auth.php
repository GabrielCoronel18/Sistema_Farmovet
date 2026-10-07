<?php
namespace Gabriel\SistemaFarmovet\helpers;

function auth(): bool {
    return isset($_SESSION['usuario']) && is_array($_SESSION['usuario']);
}

function obtenerIdRolSesion(): int {
    $usuario = $_SESSION['usuario'] ?? [];
    if (isset($usuario[0]) && is_array($usuario[0])) {
        $usuario = $usuario[0];
    }

    return (int) ($usuario['id_rol'] ?? 0);
}

function verificarRol(array $roles): void {
    if (!auth()) {
        header("Location: index.php?url=Login");
        exit();
    }

    $rolesPermitidos = array_map('intval', $roles);
    if (!in_array(obtenerIdRolSesion(), $rolesPermitidos, true)) {
        http_response_code(403);
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'No tienes permisos para realizar esta acción.'
            ]);
        } else {
            echo 'No tienes permisos para acceder a esta sección.';
        }
        exit();
    }
}