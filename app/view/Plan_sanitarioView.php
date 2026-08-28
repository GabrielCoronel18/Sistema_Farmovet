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
            
            <a href="?url=PlanSanitario" class="nav-link-custom active">
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
                <h2 class="fw-bold text-purple mb-0">Planes Sanitarios</h2> 
                <p class="text-muted">Gestión y control preventivo de aplicaciones médicas</p>
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
                    <li><a class="dropdown-item py-2 text-danger" href="Login"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                </ul>
            </div>
        </header>

        <div class="card shadow-sm border-0 mb-4" style="border-left: 4px solid #7b1fa2; border-radius: 10px;">
            <div class="card-body p-4">
                <form id="form-plan-sanitario">
                    <input type="hidden" name="action_form" id="action_form" value="registrar">
                    <input type="hidden" name="id_plan" id="id_plan" value="">
                    
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-purple" style="color: #4a148c;">ID Mascota</label>
                            <input type="number" name="id_mascota" id="id_mascota" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-purple" style="color: #4a148c;">ID Medicamento</label>
                            <input type="number" name="id_medicamento" id="id_medicamento" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-purple" style="color: #4a148c;">Fecha Aplicación</label>
                            <input type="date" name="fecha_aplicacion" id="fecha_aplicacion" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-purple" style="color: #4a148c;">Próximo Refuerzo</label>
                            <input type="date" name="proximo_refuerzo" id="proximo_refuerzo" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                          <button type="submit" id="btn-guardar-plan" class="btn btn-success w-100 fw-semibold">
    <i class="bi bi-save me-1"></i> Guardar
</button>
                            <button type="button" id="btn-cancelar-edicion" class="btn btn-secondary w-100 mt-1 d-none">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped align-middle text-nowrap bg-white m-0">
                <thead>
                    <tr>
                        <th class="text-white" style="background-color: #4a148c;">ID Plan</th>
                        <th class="text-white" style="background-color: #4a148c;">Mascota</th>
                        <th class="text-white" style="background-color: #4a148c;">Medicamento</th>
                        <th class="text-white" style="background-color: #4a148c;">Aplicación</th>
                        <th class="text-white" style="background-color: #4a148c;">Próximo Refuerzo</th>
                        <th class="text-white text-center" style="background-color: #4a148c;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-planes-sanitarios">
                    </tbody>
            </table>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let registrosLocales = [];

    // Esta función usa el parámetro correcto de la URL para pedir los datos mediante Fetch
    function cargarPlanesSanitarios() {
        const urlParams = new URLSearchParams(window.location.search);
        const moduloUrl = urlParams.get('url') || 'PlanSanitario';

        const formData = new FormData();
        formData.append('obtener', '1');

        fetch(`?url=${moduloUrl}`, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('tabla-planes-sanitarios');
            tbody.innerHTML = '';
            registrosLocales = data.resultados;

            if (!registrosLocales || registrosLocales.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay planes sanitarios registrados en el sistema.</td></tr>';
            } else {
                registrosLocales.forEach(p => {
                    const fila = `
                        <tr class="table-light">
                            <td><strong>#${p.id_plan}</strong></td>
                            <td>${p.nombre_mascota || 'Mascota'} <span class="text-muted small">(ID: ${p.id_mascota})</span></td>
                            <td>${p.nombre_medicamento || 'Medicamento'} <span class="text-muted small">(ID: ${p.id_medicamento})</span></td>
                            <td>${p.fecha_aplicacion}</td>
                            <td class="fw-bold" style="color: #7b1fa2;">${p.proximo_refuerzo}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success me-1" onclick="prepararEdicion(${p.id_plan})"><i class="bi bi-pencil-square"></i> Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarRegistro(${p.id_plan})"><i class="bi bi-trash"></i> Eliminar</button>
                            </td>
                        </tr>`;
                    tbody.insertAdjacentHTML('beforeend', fila);
                });
            }
        }).catch(err => {
            console.error("Error:", err);
            document.getElementById('tabla-planes-sanitarios').innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay planes sanitarios registrados.</td></tr>';
        });
    }

    // Interceptar el envío del formulario para procesar de forma asíncrona
    document.getElementById('form-plan-sanitario').addEventListener('submit', function(e) {
        e.preventDefault();
        const urlParams = new URLSearchParams(window.location.search);
        const moduloUrl = urlParams.get('url') || 'PlanSanitario';
        const formData = new FormData(this);

        fetch(`?url=${moduloUrl}`, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: '¡Guardado!', text: 'El registro se procesó con éxito.', confirmButtonColor: '#7b1fa2' });
                resetearFormulario();
                cargarPlanesSanitarios();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un problema en la base de datos.' });
            }
        });
    });

    function prepararEdicion(id) {
        const registro = registrosLocales.find(r => r.id_plan == id);
        if (registro) {
            document.getElementById('action_form').value = 'actualizar';
            document.getElementById('id_plan').value = registro.id_plan;
            document.getElementById('id_mascota').value = registro.id_mascota;
            document.getElementById('id_medicamento').value = registro.id_medicamento;
            document.getElementById('fecha_aplicacion').value = registro.fecha_aplicacion;
            document.getElementById('proximo_refuerzo').value = registro.proximo_refuerzo;
            
            document.getElementById('btn-guardar-plan').innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizar';
            document.getElementById('btn-cancelar-edicion').classList.remove('d-none');
        }
    }

    function eliminarRegistro(id) {
        const urlParams = new URLSearchParams(window.location.search);
        const moduloUrl = urlParams.get('url') || 'PlanSanitario';

        Swal.fire({
            title: "¿Seguro que deseas eliminar?",
            text: "El registro cambiará su estado a inactivo.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#7b1fa2",
            cancelButtonColor: "#d33",
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
                        Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Registro dado de baja.', confirmButtonColor: '#7b1fa2' });
                        cargarPlanesSanitarios();
                    }
                });
            }
        });
    }

    function resetearFormulario() {
        document.getElementById('form-plan-sanitario').reset();
        document.getElementById('action_form').value = 'registrar';
        document.getElementById('id_plan').value = '';
        document.getElementById('btn-guardar-plan').innerHTML = '<i class="bi bi-save me-1"></i> Guardar';
        document.getElementById('btn-cancelar-edicion').classList.add('d-none');
    }

    document.getElementById('btn-cancelar-edicion').addEventListener('click', resetearFormulario);

    // Arrancar la carga de datos al abrir la página
    cargarPlanesSanitarios();
</script>
</body>
</html>
