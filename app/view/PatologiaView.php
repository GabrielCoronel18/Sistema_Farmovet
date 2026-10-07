<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Patologías</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <h2 class="fw-bold text-purple mb-0">Patologías</h2>
                    <p class="text-muted">Gestión de Patologías</p>
                </div>
                <?php require_once __DIR__ . '/componente/user.php'; ?>
            </header>

            <div class="d-flex justify-content-end mb-3">
                <div class="me-3">
                    <input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar">
                </div>
                <button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="bi bi-plus"></i> Agregar Patología
                </button>
            </div>

            <div class="table-responsive shadow-sm rounded">
                <table class="table table-striped align-middle text-nowrap">
                    <thead>
                        <th class="table-purple">Id</th>
                        <th class="table-purple">Nombre</th>
                        <th class="table-purple">Tipo</th>
                        <th class="table-purple">Síntomas</th>
                        <th class="table-purple">Acciones</th>
                    </thead>
                    <tbody id="TablaPatologias">
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="TituloModalPatologia"></h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" class="PatologiaForm">
                    <div class="modal-body">
                        <input type="hidden" id="id_patologia" name="id_patologia">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <input type="text" class="form-control" id="tipo" name="tipo" required>
                        </div>
                        <div class="mb-3">
                            <label for="sintomas" class="form-label">Síntomas</label>
                            <textarea class="form-control" id="sintomas" name="sintomas" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success btn-agregar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/sweetalert2.min.js"></script>
    <script src="public/js/alerts.js"></script>
    <script src="public/js/Patologia.js"></script>
</body>
</html>