<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Rol</title>
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
                <h2 id="titulo-form" class="fw-bold text-purple mb-0">Nuevo Rol</h2>
                <p class="text-muted">Complete los datos del rol</p>
            </div>
            <?php require_once __DIR__ . '/componente/user.php'; ?>
        </header>

        <div class="card shadow-sm">
            <div class="card-body">
                <form id="form-rol">
                    <input type="hidden" id="id" name="id" value="">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="?url=Rol" class="btn btn-secondary me-2">Cancelar</a>
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
    const id = urlParams.get('id');

    if (accion === 'editar' && id) {
        document.getElementById('titulo-form').textContent = 'Editar Rol';
        document.getElementById('btn-submit').innerHTML = '<i class="bi bi-save"></i> Actualizar';
        const fd = new FormData();
        fd.append('obtenerRol', '1');
        fd.append('id', id);
        fetch('?url=Rol', { method: 'POST', body: fd })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('id').value = data.resultado.id_rol;
                    document.getElementById('nombre').value = data.resultado.nombre_rol;
                } else {
                    Swal.fire('Error', 'No se encontró el rol', 'error').then(() => window.location.href = '?url=Rol');
                }
            });
    }

    document.getElementById('form-rol').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        if (accion === 'editar') {
            formData.append('actualizar', '1');
        } else {
            formData.append('agregar', '1');
        }

        fetch('?url=Rol', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: accion === 'crear' ? '¡Rol creado!' : '¡Rol actualizado!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => window.location.href = '?url=Rol');
                } else {
                    Swal.fire('Error', data.mensaje || 'Operación fallida', 'error');
                }
            });
    });
</script>
</body>
</html>