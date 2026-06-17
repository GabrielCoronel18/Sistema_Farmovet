<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>
<div class="d-flex">
    <nav class="sidebar d-flex flex-column">
        <div class="sidebar-brand">
            <i class="bi bi-heart-pulse-fill me-2"></i>Farmovet
        </div>
        <div class="d-flex flex-column w-100 mb-auto">
            <a href="?url=Dashboard" class="nav-link-custom">
                <div><i class="bi bi-grid-1x2-fill me-2"></i> Inicio</div>
            </a>
            <a href="#menuConsultas" data-bs-toggle="collapse" class="nav-link-custom" aria-expanded="false">
                <div><i class="bi bi-file-earmark-medical me-2"></i> Consultas</div>
                <i class="bi bi-chevron-down arrow-icon"></i>
            </a>
            <div class="collapse" id="menuConsultas">
                <div class="submenu">
                    <a href="?url=NuevaConsulta" class="nav-link-sub">Nueva Consulta</a>
                    <a href="?url=Consulta" class="nav-link-sub">Historial Clinico</a>
                </div>
            </div>
            <a href="?url=Mascota" class="nav-link-custom">
                <div><i class="fa-solid fa-paw me-2"></i> Mascotas</div>
            </a>
            <a href="?url=Cliente" class="nav-link-custom">
                <div><i class="bi bi-people-fill me-2"></i> Clientes</div>
            </a>
            <a href="?url=agente" class="nav-link-custom">
                <div><i class="bi bi-shield-plus me-2"></i> Planes Sanitarios</div>
            </a>
            <a href="#menuConfiguracion" data-bs-toggle="collapse" class="nav-link-custom" aria-expanded="false">
                <div><i class="bi bi-gear-fill me-2"></i> Configuracion</div>
                <i class="bi bi-chevron-down arrow-icon"></i>
            </a>
            <div class="collapse" id="menuConfiguracion">
                <div class="submenu">
                    <a href="?url=Razas" class="nav-link-sub">Razas</a>
                    <a href="?url=Especies" class="nav-link-sub">Especies</a>
                    <a href="?url=Medicamento" class="nav-link-sub">Medicamentos</a>
                    <a href="?url=TipoMedicamento" class="nav-link-sub">Tipos de Medicamento</a>
                    <a href="?url=Alergia" class="nav-link-sub">Alergias</a>
                    <a href="?url=Patologia" class="nav-link-sub">Patologias</a>
                </div>
            </div>
            <a href="#menuSeguridad" data-bs-toggle="collapse" class="nav-link-custom active" aria-expanded="true">
                <div><i class="bi bi-shield-lock-fill me-2"></i> Seguridad</div>
                <i class="bi bi-chevron-down arrow-icon"></i>
            </a>
            <div class="collapse show" id="menuSeguridad">
                <div class="submenu">
                    <a href="?url=Usuario" class="nav-link-sub active">Usuarios</a>
                    <a href="?url=Rol" class="nav-link-sub">Roles</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-purple mb-0">Usuarios</h2>
                <p class="text-muted">Gestión de usuarios</p>
            </div>
            <div class="dropdown">
                <button class="btn profile-dropdown-btn d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-start d-none d-sm-block" style="line-height: 1.1;">
                        <span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;">Usuario</span>
                        <span class="text-muted" style="font-size: 0.75rem;">Administrador</span>
                    </div>
                    <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 10px;">
                    <li><a class="dropdown-item py-2" href="?url=Usuario"><i class="bi bi-person me-2 text-purple"></i>Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger" href="Login"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </header>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                    switch ($_GET['mensaje']) {
                        case 'creado': echo '¡Usuario creado correctamente!'; break;
                        case 'actualizado': echo '¡Usuario actualizado correctamente!'; break;
                        case 'eliminado': echo '¡Usuario eliminado correctamente!'; break;
                        default: echo 'Operación realizada';
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-end mb-3">
            <div class="me-3">
                <form method="get" class="d-flex">
                    <input type="hidden" name="url" value="Usuario">
                    <input type="text" name="buscar" class="form-control" placeholder="Filtrar usuarios..." value="<?= htmlspecialchars($busqueda ?? '') ?>">
                    <button type="submit" class="btn btn-outline-secondary ms-2"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <a href="?url=Usuario&accion=crear" class="btn btn-success"><i class="bi bi-plus"></i> Agregar Usuario</a>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="table-purple">Cédula</th>
                        <th class="table-purple">Nombre</th>
                        <th class="table-purple">Apellido</th>
                        <th class="table-purple">Teléfono</th>
                        <th class="table-purple">Correo</th>
                        <th class="table-purple">Rol</th>
                        <th class="table-purple">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr class="table-light">
                                <td><?= htmlspecialchars($u['cedula_usuario']) ?></td>
                                <td><?= htmlspecialchars($u['nombre']) ?></td>
                                <td><?= htmlspecialchars($u['apellido']) ?></td>
                                <td><?= htmlspecialchars($u['telefono']) ?></td>
                                <td><?= htmlspecialchars($u['correo']) ?></td>
                                <td><?= htmlspecialchars($u['nombre_rol']) ?></td>
                                <td>
                                    <a href="?url=Usuario&accion=editar&cedula=<?= urlencode($u['cedula_usuario']) ?>" class="btn btn-sm btn-success">Editar</a>
                                    <a href="?url=Usuario&accion=eliminar&cedula=<?= urlencode($u['cedula_usuario']) ?>" class="btn btn-sm btn-danger btn-eliminar">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <?php if (isset($pagina) && $pagina > 1): ?>
                    <a href="?url=Usuario&pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($busqueda ?? '') ?>" class="btn btn-outline-secondary btn-sm">Anterior</a>
                <?php endif; ?>
                <?php if (isset($pagina) && !empty($usuarios) && count($usuarios) === 5): ?>
                    <a href="?url=Usuario&pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($busqueda ?? '') ?>" class="btn btn-outline-secondary btn-sm">Siguiente</a>
                <?php endif; ?>
            </div>
            <span class="text-muted">Página <?= $pagina ?? 1 ?></span>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/Dashboard.js"></script>
</body>
</html>