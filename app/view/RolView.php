<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Roles</title>
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
                <h2 class="fw-bold text-purple mb-0">Roles</h2>
                <p class="text-muted">Gestión de Roles del Sistema</p>
            </div>
           <?php require_once __DIR__ . '/componente/user.php'; ?>
        </header>
        <div class="d-flex justify-content-end mb-3">
            <div class="me-3">
                <form id="form-buscar" class="d-flex">
                    <input type="text" id="input-buscar" class="form-control" placeholder="Filtrar roles...">
                    <button type="submit" class="btn btn-outline-secondary ms-2"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <a href="?url=Rol&accion=crear" class="btn btn-success"><i class="bi bi-plus"></i> Agregar Rol</a>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="table-purple">ID</th>
                        <th class="table-purple">Nombre del Rol</th>
                        <th class="table-purple">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-roles"></tbody>
            </table>
        </div>

        <div id="paginacion" class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <button id="btn-anterior" class="btn btn-outline-secondary btn-sm" disabled>Anterior</button>
                <button id="btn-siguiente" class="btn btn-outline-secondary btn-sm">Siguiente</button>
            </div>
            <span id="info-pagina" class="text-muted">Página 1</span>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let paginaActual = 1;
    const limite = 10;

    function cargarRoles(pagina, busqueda = '') {
        const formData = new FormData();
        formData.append('obtener', '1');
        formData.append('pagina', pagina);
        formData.append('limite', limite);
        if (busqueda) formData.append('parametro', busqueda);

        fetch('?url=Rol', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tabla-roles');
                tbody.innerHTML = '';
                if (data.resultados.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">No se encontraron roles.</td></tr>';
                } else {
                    data.resultados.forEach(rol => {
                        const fila = `
                            <tr class="table-light">
                                <td>${rol.id_rol}</td>
                                <td>${rol.nombre_rol}</td>
                                <td>
                                    <a href="?url=Rol&accion=editar&id=${rol.id_rol}" class="btn btn-sm btn-success">Actualizar</a>
                                    <button class="btn btn-sm btn-danger btn-eliminar" data-id="${rol.id_rol}">Eliminar</button>
                                </td>
                            </tr>`;
                        tbody.insertAdjacentHTML('beforeend', fila);
                    });
                }
                document.getElementById('btn-anterior').disabled = (pagina <= 1);
                document.getElementById('btn-siguiente').disabled = (data.resultados.length < limite);
                document.getElementById('info-pagina').textContent = `Página ${pagina}`;
                paginaActual = pagina;
            });
    }

    document.getElementById('form-buscar').addEventListener('submit', function(e) {
        e.preventDefault();
        cargarRoles(1, document.getElementById('input-buscar').value);
    });

    document.getElementById('btn-anterior').addEventListener('click', function() {
        if (paginaActual > 1) cargarRoles(paginaActual - 1, document.getElementById('input-buscar').value);
    });

    document.getElementById('btn-siguiente').addEventListener('click', function() {
        cargarRoles(paginaActual + 1, document.getElementById('input-buscar').value);
    });

    document.querySelector('#tabla-roles').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-eliminar')) {
            const id = e.target.getAttribute('data-id');
            Swal.fire({
                title: "Confirmar Eliminación",
                text: "Esta acción no se puede revertir",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#18994a",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('eliminar', '1');
                    formData.append('id', id);
                    fetch('?url=Rol', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire('Eliminado', '', 'success');
                                cargarRoles(paginaActual, document.getElementById('input-buscar').value);
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar', 'error');
                            }
                        });
                }
            });
        }
    });

    cargarRoles(1);
</script>
</body>
</html>