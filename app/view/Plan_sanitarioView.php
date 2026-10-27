<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Planes Sanitarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
    <style>
        /* Color morado acorde al Sidebar */
        .bg-purple-header {
            background-color: #673ab7 !important;
            color: #ffffff !important;
        }
        /* Resaltado de filas pendientes/vencidas */
        .tr-alerta-vencido {
            background-color: #ffebee !important;
        }
        /* Badge o círculo de alerta */
        .badge-notif-red {
            background-color: #dc3545;
            color: #fff;
            font-size: 0.75rem;
            padding: 0.25em 0.55em;
            border-radius: 50rem;
            font-weight: 700;
        }
    </style>
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
            
            <a href="?url=PlanSanitario" class="nav-link-custom active d-flex justify-content-between align-items-center">
                <div><i class="bi bi-shield-plus me-2"></i> Planes Sanitarios</div>
                <!-- Círculo rojo / Badge indicador de alertas -->
                <span id="badge-alertas-sidebar" class="badge-notif-red d-none">0</span>
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
                    <a href="?url=Presentacion" class="nav-link-sub">Presentaciones</a>
                    <a href="?url=Alergia" class="nav-link-sub">Alergias</a>
                    <a href="?url=Cirugia" class="nav-link-sub">Cirugías</a>
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
        
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0" style="color: #673ab7;">Planes Sanitarios</h2> 
                <p class="text-muted mb-0">Gestión y control preventivo de aplicaciones médicas</p>
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
                    <li><a class="dropdown-item py-2" href="?url=Usuario"><i class="bi bi-person me-2" style="color: #673ab7;"></i>Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger" href="Login"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </header>

        <!-- Banner de aviso general si hay refuerzos pendientes o cumplidos -->
        <div id="banner-alerta" class="alert alert-danger d-none align-items-center shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
                <strong id="texto-alerta-titulo">¡Atención!</strong>
                <span id="texto-alerta-cuerpo"> Existen refuerzos de planes sanitarios que se cumplen hoy o están vencidos.</span>
            </div>
        </div>

        <!-- Tarjeta para Edición -->
        <div id="card-edicion" class="card shadow-sm border-0 mb-4 d-none" style="border-left: 4px solid #198754; border-radius: 10px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-pencil-square me-2"></i>Modificar Plan Sanitario</h5>
                    <button type="button" class="btn-close" onclick="cancelarEdicion()" aria-label="Cerrar"></button>
                </div>
                <form id="form-edicion-plan">
                    <input type="hidden" name="action_form" value="actualizar">
                    <input type="hidden" name="id_plan" id="id_plan_edit" value="">
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold" style="color: #673ab7;">ID Mascota</label>
                            <input type="number" name="id_mascota" id="id_mascota_edit" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="color: #673ab7;">ID Medicamento</label>
                            <input type="number" name="id_medicamento" id="id_medicamento_edit" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="color: #673ab7;">Fecha Aplicación</label>
                            <input type="date" name="fecha_aplicacion" id="fecha_aplicacion_edit" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold" style="color: #673ab7;">Próximo Refuerzo</label>
                            <input type="date" name="proximo_refuerzo" id="proximo_refuerzo_edit" class="form-control" required>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-success w-100 fw-semibold">
                                <i class="bi bi-save me-1"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-secondary fw-semibold" onclick="cancelarEdicion()">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Barra de Controles: Filtrar + Agregar Plan -->
        <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
            <div style="width: 250px;">
                <input type="text" id="input-filtrar" class="form-control" placeholder="Filtrar" onkeyup="filtrarTabla()" style="border-color: #0d6efd;">
            </div>
            <button type="button" class="btn btn-success text-white fw-semibold px-3 py-2" style="border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#modalRegistroPlan" onclick="prepararNuevoRegistro()">
                + Agregar Plan
            </button>
        </div>

        <!-- Tabla con Encabezado Morado a juego con el Sidebar -->
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped align-middle text-nowrap bg-white m-0">
                <thead>
                    <tr class="bg-purple-header">
                        <th class="bg-purple-header">ID Plan</th>
                        <th class="bg-purple-header">Mascota</th>
                        <th class="bg-purple-header">Medicamento</th>
                        <th class="bg-purple-header">Aplicación</th>
                        <th class="bg-purple-header">Próximo Refuerzo</th>
                        <th class="bg-purple-header text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-planes-sanitarios">
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Modal para Registrar Plan -->
<div class="modal fade" id="modalRegistroPlan" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #673ab7;">
        <h5 class="modal-title" id="modalLabel"><i class="bi bi-shield-plus me-2"></i>Registrar Plan Sanitario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="form-modal-plan">
          <div class="modal-body">
                <input type="hidden" name="action_form" value="registrar">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #673ab7;">ID Mascota</label>
                    <input type="number" name="id_mascota" class="form-control" placeholder="ID registrado de la mascota" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #673ab7;">ID Medicamento</label>
                    <input type="number" name="id_medicamento" class="form-control" placeholder="ID registrado del medicamento" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #673ab7;">Fecha Aplicación</label>
                    <input type="date" name="fecha_aplicacion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #673ab7;">Próximo Refuerzo</label>
                    <input type="date" name="proximo_refuerzo" class="form-control" required>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success fw-semibold">
                <i class="bi bi-save me-1"></i> Guardar Registro
            </button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let registrosLocales = [];

    function cargarPlanesSanitarios() {
        const urlParams = new URLSearchParams(window.location.search);
        const moduloUrl = urlParams.get('url') || 'PlanSanitario';

        const formData = new FormData();
        formData.append('obtener', '1');

        fetch(`?url=${moduloUrl}`, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            registrosLocales = data.resultados || [];
            renderizarTabla(registrosLocales);
        }).catch(err => {
            console.error("Error al cargar:", err);
            document.getElementById('tabla-planes-sanitarios').innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay planes sanitarios registrados.</td></tr>';
        });
    }

    function renderizarTabla(lista) {
        const tbody = document.getElementById('tabla-planes-sanitarios');
        tbody.innerHTML = '';

        if (!lista || lista.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No se encontraron planes sanitarios.</td></tr>';
            actualizarNotificaciones(0);
            return;
        }

        // Obtener la fecha de hoy en formato AAAA-MM-DD local
        const hoy = new Date().toISOString().split('T')[0];
        let contadorVencidos = 0;

        lista.forEach(p => {
            const nombreMascota = p.nombre_mascota || '';
            const nombreMedicamento = p.nombre_medicamento || '';
            const fechaRefuerzo = p.proximo_refuerzo || '';

            // Comprobar si el refuerzo ya se cumplió o venció
            const esFechaCumplida = fechaRefuerzo !== '' && fechaRefuerzo <= hoy;

            if (esFechaCumplida) {
                contadorVencidos++;
            }

            const claseFila = esFechaCumplida ? 'tr-alerta-vencido' : 'table-light';
            const badgeRefuerzo = esFechaCumplida 
                ? `<span class="badge bg-danger ms-1"><i class="bi bi-bell-fill me-1"></i>¡Día de Refuerzo / Vencido!</span>`
                : `<span class="badge bg-success ms-1"><i class="bi bi-check-circle me-1"></i>En regla</span>`;

            const fila = `
                <tr class="${claseFila}">
                    <td><strong>#${p.id_plan}</strong></td>
                    <td>${nombreMascota ? nombreMascota : 'Mascota'} <span class="text-muted small">(ID: ${p.id_mascota})</span></td>
                    <td>${nombreMedicamento ? nombreMedicamento : 'Medicamento'} <span class="text-muted small">(ID: ${p.id_medicamento})</span></td>
                    <td>${p.fecha_aplicacion}</td>
                    <td>
                        <strong class="${esFechaCumplida ? 'text-danger' : ''}">${p.proximo_refuerzo}</strong>
                        ${badgeRefuerzo}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success me-1" onclick="prepararEdicion(${p.id_plan})">
                            <i class="bi bi-pencil-square me-1"></i> Actualizar
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarRegistro(${p.id_plan})">
                            <i class="bi bi-trash me-1"></i> Eliminar
                        </button>
                    </td>
                </tr>`;
            tbody.insertAdjacentHTML('beforeend', fila);
        });

        actualizarNotificaciones(contadorVencidos);
    }

    // Actualiza el círculo rojo del sidebar y la barra de alerta superior
    function actualizarNotificaciones(contador) {
        const badgeSidebar = document.getElementById('badge-alertas-sidebar');
        const bannerAlerta = document.getElementById('banner-alerta');
        const cuerpoAlerta = document.getElementById('texto-alerta-cuerpo');

        if (contador > 0) {
            badgeSidebar.innerText = contador;
            badgeSidebar.classList.remove('d-none');

            cuerpoAlerta.innerText = ` Hay ${contador} plan(es) sanitario(s) que requieren aplicación de refuerzo hoy o están pendientes.`;
            bannerAlerta.classList.remove('d-none');
            bannerAlerta.classList.add('d-flex');
        } else {
            badgeSidebar.classList.add('d-none');
            bannerAlerta.classList.add('d-none');
            bannerAlerta.classList.remove('d-flex');
        }
    }

    function filtrarTabla() {
        const busqueda = document.getElementById('input-filtrar').value.toLowerCase().trim();
        if (!busqueda) {
            renderizarTabla(registrosLocales);
            return;
        }

        const filtrados = registrosLocales.filter(p => {
            const mascota = (p.nombre_mascota || '').toLowerCase();
            const idMascota = (p.id_mascota || '').toString();
            const medicamento = (p.nombre_medicamento || '').toLowerCase();
            const idMedicamento = (p.id_medicamento || '').toString();
            const idPlan = (p.id_plan || '').toString();

            return mascota.includes(busqueda) || 
                   idMascota.includes(busqueda) || 
                   medicamento.includes(busqueda) || 
                   idMedicamento.includes(busqueda) || 
                   idPlan.includes(busqueda);
        });

        renderizarTabla(filtrados);
    }

    document.getElementById('form-modal-plan').addEventListener('submit', function(e) {
        e.preventDefault();
        enviarFormulario(new FormData(this), true);
    });

    document.getElementById('form-edicion-plan').addEventListener('submit', function(e) {
        e.preventDefault();
        enviarFormulario(new FormData(this), false);
    });

    function enviarFormulario(formData, esModal) {
        const urlParams = new URLSearchParams(window.location.search);
        const moduloUrl = urlParams.get('url') || 'PlanSanitario';

        fetch(`?url=${moduloUrl}`, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: '¡Guardado!', text: 'El registro se procesó con éxito.', confirmButtonColor: '#198754' });
                
                if (esModal) {
                    const modalEl = document.getElementById('modalRegistroPlan');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    document.getElementById('form-modal-plan').reset();
                } else {
                    cancelarEdicion();
                }

                cargarPlanesSanitarios();
            } else {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error de inserción', 
                    text: data.mensaje || 'Verifica que el ID de la Mascota y Medicamento existan previamente.' 
                });
            }
        });
    }

    function prepararNuevoRegistro() {
        document.getElementById('form-modal-plan').reset();
    }

    function prepararEdicion(id) {
        const registro = registrosLocales.find(r => r.id_plan == id);
        if (registro) {
            document.getElementById('id_plan_edit').value = registro.id_plan;
            document.getElementById('id_mascota_edit').value = registro.id_mascota;
            document.getElementById('id_medicamento_edit').value = registro.id_medicamento;
            document.getElementById('fecha_aplicacion_edit').value = registro.fecha_aplicacion;
            document.getElementById('proximo_refuerzo_edit').value = registro.proximo_refuerzo;
            
            document.getElementById('card-edicion').classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function cancelarEdicion() {
        document.getElementById('form-edicion-plan').reset();
        document.getElementById('card-edicion').classList.add('d-none');
    }

    function eliminarRegistro(id) {
        const urlParams = new URLSearchParams(window.location.search);
        const moduloUrl = urlParams.get('url') || 'PlanSanitario';

        Swal.fire({
            title: "¿Seguro que deseas eliminar?",
            text: "El registro cambiará su estado a inactivo.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('eliminar', '1');
                formData.append('id_plan', id);

                fetch(`?url=${moduloUrl}`, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Registro dado de baja.', confirmButtonColor: '#dc3545' });
                        cargarPlanesSanitarios();
                    }
                });
            }
        });
    }

    cargarPlanesSanitarios();
</script>
</body>
</html>