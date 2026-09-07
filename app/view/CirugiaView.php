<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Farmovet - Cirugías</title>
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
					<h2 class="fw-bold text-purple mb-0">Cirugías</h2>
					<p class="text-muted">Gestión del catálogo de cirugías</p>
				</div>
				<div class="dropdown">
					<button class="btn profile-dropdown-btn d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<div class="text-start d-none d-sm-block" style="line-height: 1.1;">
							<span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;">Usuario</span>
							<span class="text-muted" style="font-size: 0.75rem;">Administrador</span>
						</div>
						<i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
						<li><a class="dropdown-item py-2" href="?url=Usuario"><i class="bi bi-person me-2 text-purple"></i>Perfil</a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item py-2 text-danger" href="?url=Login"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
					</ul>
				</div>
			</header>

			<div class="d-flex justify-content-end mb-3 gap-3">
				<input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar" style="max-width: 260px;">
				<button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
					<i class="bi bi-plus"></i> Agregar Cirugía
				</button>
			</div>

			<div class="table-responsive shadow-sm rounded">
				<table class="table table-striped align-middle text-nowrap">
					<thead>
						<tr>
							<th class="table-purple">Id</th>
							<th class="table-purple">Nombre</th>
							<th class="table-purple">Gravedad</th>
							<th class="table-purple">Acciones</th>
						</tr>
					</thead>
					<tbody id="TablaCirugias"></tbody>
				</table>
			</div>
		</main>
	</div>

	<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="TituloModalCirugia"></h1>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<form method="post" class="CirugiaForm">
					<div class="modal-body">
						<input type="hidden" id="id_cirugia" name="id_cirugia">
						<div class="mb-3">
							<label for="nombre_cirugia" class="form-label">Nombre</label>
							<input type="text" class="form-control" id="nombre_cirugia" name="nombre_cirugia" maxlength="120" required>
						</div>
						<div class="mb-3">
							<label for="gravedad" class="form-label">Gravedad</label>
							<select class="form-select" id="gravedad" name="gravedad" required>
                                <option value="" selected disabled>Seleccione...</option>
                                <option value="Grave">Grave</option>
                                <option value="Moderada">Moderada</option>
                                <option value="Leve">Leve</option>
                            </select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-success">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
	<script src="public/js/sweetalert2.min.js"></script>
	<script src="public/js/alerts.js"></script>
	<script src="public/js/cirugia.js"></script>
</body>
</html>
