<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Usuario</title>
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
                <h2 id="titulo-form" class="fw-bold text-purple mb-0">Nuevo Usuario</h2>
                <p id="subtitulo-form" class="text-muted">Complete los datos para registrar</p>
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

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="form-usuario">
                    <input type="hidden" id="cedula_original" name="cedula_original" value="">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contraseña" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                        </div>
                        <div class="col-md-6">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <a href="?url=Usuario" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-success" id="btn-submit">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const accion = urlParams.get('accion') || 'crear';
    const cedula = urlParams.get('cedula');

    if (accion === 'editar' && cedula) {
        document.getElementById('titulo-form').textContent = 'Editar Usuario';
        document.getElementById('subtitulo-form').textContent = 'Modifique los datos necesarios';
        document.getElementById('btn-submit').innerHTML = '<i class="bi bi-save"></i> Actualizar';
        document.getElementById('contraseña').removeAttribute('required');
        document.getElementById('contraseña').placeholder = 'Dejar vacío para no cambiar';
    }

    const promesas = [];
    promesas.push(fetch('?url=Usuario', {
        method: 'POST',
        body: (() => { const fd = new FormData(); fd.append('obtenerRoles', '1'); return fd; })()
    }).then(r => r.json()));

    if (accion === 'editar' && cedula) {
        const fd = new FormData();
        fd.append('obtenerUsuario', '1');
        fd.append('cedula', cedula);
        promesas.push(fetch('?url=Usuario', { method: 'POST', body: fd }).then(r => r.json()));
    } else {
        promesas.push(Promise.resolve(null));
    }

    Promise.all(promesas).then(([rolesRes, usuarioRes]) => {
        const selectRol = document.getElementById('rol');
        if (rolesRes.status === 'success') {
            rolesRes.resultados.forEach(rol => {
                const opt = document.createElement('option');
                opt.value = rol.id_rol;
                opt.textContent = rol.nombre_rol;
                if (usuarioRes && usuarioRes.resultado && usuarioRes.resultado.id_rol == rol.id_rol) {
                    opt.selected = true;
                }
                selectRol.appendChild(opt);
            });
        }

        if (usuarioRes && usuarioRes.status === 'success') {
            const u = usuarioRes.resultado;
            document.getElementById('cedula_original').value = u.cedula_usuario;
            document.getElementById('cedula').value = u.cedula_usuario;
            document.getElementById('nombre').value = u.nombre;
            document.getElementById('apellido').value = u.apellido;
            document.getElementById('telefono').value = u.telefono || '';
            document.getElementById('correo').value = u.correo;
        }
    });

    document.getElementById('form-usuario').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        if (accion === 'editar') {
            formData.append('actualizar', '1');
        } else {
            formData.append('agregar', '1');
        }

        fetch('?url=Usuario', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: accion === 'crear' ? '¡Usuario creado!' : '¡Usuario actualizado!',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => window.location.href = '?url=Usuario');
            } else {
                Swal.fire('Error', data.mensaje || 'Error en la operación.', 'error');
            }
        });
    });
</script>
</body>
</html>