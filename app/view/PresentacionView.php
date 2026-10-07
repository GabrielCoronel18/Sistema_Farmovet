<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Farmovet - Presentaciones</title>
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
					<h2 class="fw-bold text-purple mb-0">Presentaciones</h2>
					<p class="text-muted">Gestión de presentaciones de medicamentos</p>
				</div>
				<?php require_once __DIR__ . '/componente/user.php'; ?>
			</header>

			<div class="d-flex justify-content-end mb-3 gap-3">
				<input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar" style="max-width: 260px;">
				<button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
					<i class="bi bi-plus"></i> Agregar Presentación
				</button>
			</div>

			<div class="table-responsive shadow-sm rounded">
				<table class="table table-striped align-middle text-nowrap">
					<thead>
						<tr>
							<th class="table-purple">Id</th>
							<th class="table-purple">Nombre</th>
							<th class="table-purple">Acciones</th>
						</tr>
					</thead>
					<tbody id="TablaPresentaciones"></tbody>
				</table>
			</div>

			<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-3">
			<div class="d-flex align-items-center gap-2">
			<label for="limite" class="form-label mb-0 text-muted">Mostrar:</label>
			<select name="limite" id="limite" class="form-select form-select-sm w-auto" style="min-width: 78px;" required>
				<option value="5" selected>5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
			
			</div>
			<nav aria-label="Paginación de tipos de medicamento">
				<ul class="pagination pagination-sm mb-0">
					<li class="page-item text-purple"><button type="button" class="page-link" id="btn-anterior">Anterior</button></li>
					<li class="page-item disabled text-purple"><span class="page-link" id="info-pagina"></span></li>
					<li class="page-item text-purple"><button type="button" class="page-link" id="btn-siguiente">Siguiente</button></li>
				</ul>
			</nav>
			</div>
		</main>
	</div>

	<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="TituloModalPresentacion"></h1>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<form method="post" class="PresentacionForm">
					<div class="modal-body">
						<input type="hidden" id="id_presentacion" name="id_presentacion">
						<div class="mb-3">
							<label for="nombre" class="form-label">Nombre</label>
							<input type="text" class="form-control" id="nombre" name="nombre" maxlength="120" required>
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
	<script src="public/js/presentacion.js"></script>
</body>
</html>
