
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Consultas</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/icons/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/icons/font-awesome/css/all.min.css">
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
                <div class="me-3">
                    <input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar">   
                </div>
                <?php if(in_array($_SESSION['usuario']['id_rol'],[1, 2], true)): ?>     
                 <div class="d-flex justify-content-end mb-3">
                    <a href="index.php?url=NuevaConsulta" class="btn btn-success"><i class="bi bi-plus"></i> Agregar consulta</a>
                </div>
                <?php endif;?>
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
                    <?php if(in_array($_SESSION['usuario']['id_rol'],[1, 2], true)): ?>
                    <th class="table-purple "id='detalles'>Detalle</th>
                    <th class="table-purple" id="acciones">Acciones</th>

                     <?php endif;?>
                </tr>
                </thead>
                <tbody id="tabla-consultas" data-url="index.php?url=Consulta">
                    <tr><td colspan="12" class="text-center text-muted py-4">Cargando consultas...</td></tr>
                </tbody>
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
			
            <nav aria-label="Paginación de consulta">
				<ul class="pagination pagination-sm mb-0">
					<li class="page-item text-purple"><button type="button" class="page-link" id="btn-anterior">Anterior</button></li>
					<li class="page-item disabled text-purple"><span class="page-link" id="info-pagina"></span></li>
					<li class="page-item text-purple"><button type="button" class="page-link" id="btn-siguiente">Siguiente</button></li>
				</ul>
			</nav>
			</div>
    </main>
</div>


<div class="modal fade" id="ReporteModal" tabindex="-1" aria-labelledby="ReporteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="ReporteModalTitle">Reporte de Consulta</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted small">Vista previa del documento PDF</span>

                    <a target="_blank" class="btn btn-md btn-outline-success" id="GenerarPDF" name="GenerarPDF">Generar</a>
                </div>

                <div class="text-center canvas-wrapper">
                    <canvas id="pdf-canvas" style="border: 1px solid #e2e8f0; max-width: 100%; height: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="public/jquery/jquery-3.7.1.min.js"></script>
<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script src="public/js/sweetalert2.min.js"></script>
<script src="public/js/ajax.js"></script>
<script src="public/js/pdfjs/pdf.min.js"></script>
<script src="public/js/consulta.js"></script>
</body>
</html>
