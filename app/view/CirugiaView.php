<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Farmovet - Cirugías</title>
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
					<h2 class="fw-bold text-purple mb-0">Cirugías</h2>
					<p class="text-muted">Gestión del catálogo de cirugías</p>
				</div>
				<?php require_once __DIR__ . '/componente/user.php'; ?>
			</header>

			<div class="d-flex justify-content-end mb-3 gap-3">
				<input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar" style="max-width: 260px;">
				<?php if($_SESSION['usuario']['id_rol'] === 1 ): ?> 
				<button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
					<i class="bi bi-plus"></i> Agregar Cirugía
				</button>
				<?php endif; ?>
			</div>

		  <article id="table-container">
				<table id="TablaCirugias" class="table table-striped" style="border-radius: 12px;">
					<thead>
						<tr>
							<th class="table-purple">Id</th>
							<th class="table-purple">Nombre</th>
							<th class="table-purple">Gravedad</th>
							<th class="table-purple">Estado</th>
							<?php if($_SESSION['usuario']['id_rol'] === 1 ): ?>  
							<th class="table-purple" id="acciones">Acciones</th>
							<?php endif; ?>
							
						</tr>
					</thead>
					<tbody></tbody>
				</table>
		  </article>

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
							<div class="invalid-feedback" id="error-nombre"></div>
						</div>
						<div class="mb-3">
							<label for="gravedad" class="form-label">Gravedad</label>
							<select class="form-select" id="gravedad" name="gravedad" required>
                                <option value="" selected disabled>Seleccione...</option>
                                <option value="Grave">Grave</option>
                                <option value="Moderada">Moderada</option>
                                <option value="Leve">Leve</option>
                            </select>
							<div class="invalid-feedback" id="error-gravedad"></div>
						</div>
						<?php if ((int) ($_SESSION["usuario"]["id_rol"] ?? 0) === 1): ?>
						<div class="mb-3">
							<label for="estado" class="form-label">Estado</label>
							<select class="form-select" id="estado" name="estado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
							<div class="invalid-feedback" id="error-estado"></div>
						</div>
						<?php endif; ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-success">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="public/jquery/jquery-3.7.1.min.js"></script>
	<script src="public/jquery/jquery.validate.min.js"></script>
	<script src="public/datatables/datatables.min.js"></script>
	<script src="public/js/ajax.js"></script>
	<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
	<script src="public/js/sweetalert2.min.js"></script>
	<script src="public/js/alerts.js"></script>
	<script src="public/js/Validate/CirugiaValidation.js"></script>
	<script src="public/js/cirugia.js"></script>
</body>
</html>
