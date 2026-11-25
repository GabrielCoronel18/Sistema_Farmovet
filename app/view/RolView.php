<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Roles</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/icons/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/icons/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="public/datatables/datatables.min.css">
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
                <?php if($_SESSION['usuario']['id_rol'] === 1 ): ?> 
            </div>
            <a href="?url=Rol&accion=crear" class="btn btn-success"><i class="bi bi-plus"></i> Agregar Rol</a>
        </div>
        <?php endif; ?>

        <div class="shadow-sm rounded">
            <table id="tabla-roles" class="table table-striped align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="table-purple">ID</th>
                        <th class="table-purple">Nombre del Rol</th>
                        <?php if($_SESSION['usuario']['id_rol'] === 1 ): ?>  
							<th class="table-purple" id="acciones">Acciones</th>
							<?php endif; ?>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

      
</div>

<script src="public/jquery/jquery-3.7.1.min.js"></script>
<script src="public/datatables/datatables.min.js"></script>
<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="public/js/sweetalert2.min.js"></script>
<script>
    const tablaRoles = $('#tabla-roles');
    const columnasRoles = [
        { data: 'id_rol', className: 'table-light' },
        { data: 'nombre_rol', className: 'table-light', render: $.fn.dataTable.render.text() }
    ];
    if ($('#acciones').length) {
        columnasRoles.push({
            data: 'id_rol',
            className: 'table-light',
            orderable: false,
            searchable: false,
            render: id => `<a href="?url=Rol&accion=editar&id=${encodeURIComponent(id)}" class="btn btn-sm btn-success">Actualizar</a>
                <button class="btn btn-sm btn-danger btn-eliminar" data-id="${encodeURIComponent(id)}">Eliminar</button>`
        });
    }

    const tabla = tablaRoles.DataTable({
        serverSide: true,
        autoWidth: false,
        scrollX: true,
        searching: true,
        lengthChange: true,
        lengthMenu: [5, 10, 20],
        pageLength: 10,
        layout: { topStart: null, topEnd: null, bottomStart: 'pageLength', bottomEnd: 'paging' },
        columns: columnasRoles,
        language: {
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
            infoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
            infoFiltered: '(filtrado de un total de _MAX_ registros)',
            loadingRecords: 'Cargando...',
            zeroRecords: 'No se encontraron resultados',
            emptyTable: 'Ningún dato disponible en esta tabla',
            paginate: { first: 'Primero', previous: 'Anterior', next: 'Siguiente', last: 'Último' }
        },
        ajax: function (request, callback) {
            const formData = new FormData();
            formData.append('obtener', '1');
            formData.append('draw', request.draw);
            formData.append('start', request.start);
            formData.append('length', request.length);
            formData.append('search[value]', request.search.value);

            fetch('?url=Rol', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data.data)) {
                        throw new Error('El servidor devolvió un formato de respuesta inválido.');
                    }
                    callback(data);
                })
                .catch(error => {
                    console.error('No se pudieron cargar los roles:', error);
                    Swal.fire('Error', 'No se pudieron cargar los roles.', 'error');
                    callback({ draw: request.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
                });
        }
    });

    $('#input-buscar').on('input', function () {
        tabla.search(this.value).draw();
    });

    $('#form-buscar').on('submit', event => event.preventDefault());

    tablaRoles.on('click', '.btn-eliminar', function() {
            const id = $(this).data('id');
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
                                tabla.ajax.reload();
                            } else {
                                Swal.fire('Error', 'No se pudo eliminar', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('No se pudo eliminar el rol:', error);
                            Swal.fire('Error', 'No se pudo eliminar el rol.', 'error');
                        });
                }
            });
    });
</script>
</body>
</html>