<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Dashboard</title>
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
                
                <a href="?url=Dashboard" class="nav-link-custom active">    <!--Elimina el "active" en la clase de esta etiqueta 
                                                                             y colocalo en la clase "nav-link-custom" del modulo que vas diseñar -->
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
                    <div><i class="bi bi-gear-fill me-2"></i></i> Configuracion</div>
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

            <a href="#menuSeguridad" data-bs-toggle="collapse" class="nav-link-custom" aria-expanded="false">
                    <div><i class="bi bi-shield-lock-fill me-2"></i> Seguridad</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse" id="menuSeguridad">
                    <div class="submenu">
                        <a href="?url=Usuario" class="nav-link-sub">Usuarios</a>
                        <a href="?url=Rol" class="nav-link-sub">Roles</a>
                    </div>
                </div>

            </div>

          
        </nav>

        <main class="main-content">
            
            <header class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-purple mb-0">Inicio</h2>        <!-- coloca el nombre  del modulo que vas a diseñar dentro del h2 
                                                                              -->
                    <p class="text-muted">Resumen general de Farmovet</p>
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
                        <li><a class="dropdown-menu-item dropdown-item py-2" href="?url=Usuario"><i class="bi bi-person me-2 text-purple"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="Login"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </header>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card p-3 stats-card bg-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-purple-light me-3">
                                <i class="bi bi-file-earmark-medical fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Consultas</h6>
                                <h4 class="mb-0 fw-bold text-purple">12</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card p-3 stats-card bg-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-purple-dark me-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Clientes</h6>
                                <h4 class="mb-0 fw-bold text-purple">45</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card p-3 stats-card bg-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-purple-light me-3">
                               <i class="fa-solid fa-paw fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Mascotas</h6>
                                <h4 class="mb-0 fw-bold text-purple">8</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card p-3 stats-card bg-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-purple-dark me-3">
                                <i class="bi bi-shield-plus fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Planes Sanitarios</h6>
                                <h4 class="mb-0 fw-bold text-purple">10</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
       