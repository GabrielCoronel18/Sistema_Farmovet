
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Consultas</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>
<div class="d-flex">
    <?php require_once __DIR__ . '/componente/menu.php'; ?>

    <main class="main-content w-100 p-4" data-consulta-error="">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-purple mb-0">Consultas</h2>
                <p class="text-muted">Historial de consultas veterinarias</p>
            </div>
            <?php require_once __DIR__ . '/componente/user.php'; ?>
        </header>

        <div class="d-flex justify-content-end mb-3">
            <a href="index.php?url=NuevaConsulta" class="btn btn-success"><i class="bi bi-plus"></i> Agregar consulta</a>
        </div>
        <div class="table-responsive consultas-tabla-contenedor shadow-sm rounded">
            <table class="table table-striped align-middle consultas-tabla">
                <thead>
                <tr>
                    <th class="table-purple">Id</th>
                    <th class="table-purple">Mascota</th>
                    <th class="table-purple">Responsable</th>
                    <th class="table-purple">Fecha</th>
                    <th class="table-purple">Tipo de ingreso</th>
                    <th class="table-purple">Remitido</th>
                    <th class="table-purple">Pronóstico</th>
                    <th class="table-purple">Tratamiento</th>
                    <th class="table-purple">Peso</th>
                    <th class="table-purple">Seguimiento</th>
                    <th class="table-purple">Detalle</th>
                    <th class="table-purple">Acciones</th>
                </tr>
                </thead>
                <tbody id="tabla-consultas" data-url="index.php?url=Consulta">
                    <tr><td colspan="12" class="text-center text-muted py-4">Cargando consultas...</td></tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/consulta.js"></script>
</body>
</html>
