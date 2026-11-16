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
        
        header("Location: index.php?url=Dashboard&error=acceso_denegado");
        exit();
    }
}