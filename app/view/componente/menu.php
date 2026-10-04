<?php
$urlActual = $_GET['url'] ?? 'Dashboard';
$rutasConsultas = ['NuevaConsulta', 'Consulta'];
$rutasCatalogos = ['Razas', 'Especies', 'Medicamento', 'TipoMedicamento', 'Presentacion', 'Alergia', 'Patologia', 'Cirugia'];
$rutasAdministracion = ['Usuario', 'Rol'];
$consultasActivas = in_array($urlActual, $rutasConsultas, true);
$catalogosActivo = in_array($urlActual, $rutasCatalogos, true);
$administracionActiva = in_array($urlActual, $rutasAdministracion, true);
?>

<nav class="sidebar d-flex flex-column">
            <div class="sidebar-brand">
                <i class="bi bi-heart-pulse-fill me-2"></i>Farmovet
            </div>
            
            <div class="d-flex flex-column w-100 mb-auto">
                
                <a href="?url=Dashboard" class="nav-link-custom <?= $urlActual === 'Dashboard' ? 'active' : '' ?>">
                    <div><i class="bi bi-grid-1x2-fill me-2"></i> Inicio</div>
                </a>
                
                <a href="#menuConsultas" data-bs-toggle="collapse" class="nav-link-custom <?= $consultasActivas ? 'active' : '' ?>" aria-expanded="<?= $consultasActivas ? 'true' : 'false' ?>">
                    <div><i class="bi bi-file-earmark-medical me-2"></i> Consultas</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse <?= $consultasActivas ? 'show' : '' ?>" id="menuConsultas">
                    <div class="submenu">
                        <a href="?url=NuevaConsulta" class="nav-link-sub <?= $urlActual === 'NuevaConsulta' ? 'active' : '' ?>">Nueva Consulta</a>
                        <a href="?url=Consulta" class="nav-link-sub <?= $urlActual === 'Consulta' ? 'active' : '' ?>">Historial Clinico</a>
                    </div>
                </div>

                <a href="?url=Mascota" class="nav-link-custom <?= $urlActual === 'Mascota' ? 'active' : '' ?>">
                    <div><i class="fa-solid fa-paw me-2"></i> Mascotas</div>
                </a>
                
                <a href="?url=Cliente" class="nav-link-custom <?= $urlActual === 'Cliente' ? 'active' : '' ?>">
                    <div><i class="bi bi-people-fill me-2"></i> Clientes</div>
                </a>
                
                <a href="?url=PlanSanitario" class="nav-link-custom <?= $urlActual === 'PlanSanitario' ? 'active' : '' ?>">
                    <div><i class="bi bi-shield-plus me-2"></i> Planes Sanitarios</div>
                </a>
                <?php if ((in_array($_SESSION['usuario']['id_rol'], [1, 2]))): ?>
                <a href="#menuConfiguracion" data-bs-toggle="collapse" class="nav-link-custom <?= $catalogosActivo ? 'active' : '' ?>" aria-expanded="<?= $catalogosActivo ? 'true' : 'false' ?>">
                    <div><i class="bi bi-gear-fill me-2"></i> Configuracion</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse <?= $catalogosActivo ? 'show' : '' ?>" id="menuConfiguracion">
                    <div class="submenu">
                        <a href="?url=Razas" class="nav-link-sub <?= $urlActual === 'Razas' ? 'active' : '' ?>">Razas</a>
                        <a href="?url=Especies" class="nav-link-sub <?= $urlActual === 'Especies' ? 'active' : '' ?>">Especies</a>
                        <a href="?url=Medicamento" class="nav-link-sub <?= $urlActual === 'Medicamento' ? 'active' : '' ?>">Medicamentos</a>
                        <a href="?url=TipoMedicamento" class="nav-link-sub <?= $urlActual === 'TipoMedicamento' ? 'active' : '' ?>">Tipos de Medicamento</a>
                        <a href="?url=Presentacion" class="nav-link-sub <?= $urlActual === 'Presentacion' ? 'active' : '' ?>">Presentaciones</a>
                        <a href="?url=Alergia" class="nav-link-sub <?= $urlActual === 'Alergia' ? 'active' : '' ?>">Alergias</a>
                        <a href="?url=Patologia" class="nav-link-sub <?= $urlActual === 'Patologia' ? 'active' : '' ?>">Patologias</a>
                        <a href="?url=Cirugia" class="nav-link-sub <?= $urlActual === 'Cirugia' ? 'active' : '' ?>">Cirugías</a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ((in_array($_SESSION['usuario']['id_rol'], [1]))): ?>
                <a href="#menuSeguridad" data-bs-toggle="collapse" class="nav-link-custom <?= $administracionActiva ? 'active' : '' ?>" aria-expanded="<?= $administracionActiva ? 'true' : 'false' ?>">
                    <div><i class="bi bi-shield-lock-fill me-2"></i> Seguridad</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse <?= $administracionActiva ? 'show' : '' ?>" id="menuSeguridad">
                    <div class="submenu">
                        <a href="?url=Usuario" class="nav-link-sub <?= $urlActual === 'Usuario' ? 'active' : '' ?>">Usuarios</a>
                        <a href="?url=Rol" class="nav-link-sub <?= $urlActual === 'Rol' ? 'active' : '' ?>">Roles</a>
                    </div>
                </div>
                 <?php endif; ?>

            </div>

          
        </nav>