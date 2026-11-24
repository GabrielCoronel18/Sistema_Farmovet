<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Rol</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/icons/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/icons/font-awesome/css/all.min.css">
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
                        <div class="invalid-feedback" id="error-nombre"></div>
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

<script src="public/jquery/jquery-3.7.1.min.js"></script>
<script src="public/jquery/jquery.validate.min.js"></script>
<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="public/js/sweetalert2.min.js"></script>
<script src="public/js/ajax.js"></script>
<script src="public/js/Validate/RolValidation.js"></script>
<script src="public/js/rol.js"></script>
</body>
</html>