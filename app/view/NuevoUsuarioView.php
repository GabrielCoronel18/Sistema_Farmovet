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
    <?php require_once __DIR__ . '/componente/menu.php'; ?>

    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 id="titulo-form" class="fw-bold text-purple mb-0">Nuevo Usuario</h2>
                <p id="subtitulo-form" class="text-muted">Complete los datos para registrar</p>
            </div>
            <?php require_once __DIR__ . '/componente/user.php'; ?>
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