<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Alergias</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/icons/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/icons/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="public/datatables/datatables.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>

   <div class="d-flex">
        <!-- Sidebar -->
       <?php require_once __DIR__ . '/componente/menu.php'; ?>

		<main class="main-content">
			<header class="d-flex justify-content-between align-items-center mb-5">
				<div>
					<h2 class="fw-bold text-purple mb-0">Alergias</h2>
					<p class="text-muted">Gestión de Alergias</p>
				</div>
				<?php require_once __DIR__ . '/componente/user.php'; ?>
			</header>

            <div class="d-flex justify-content-end mb-3">
                <div class="me-3">
                    <input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar">   
                </div>     
                <?php if($_SESSION['usuario']['id_rol'] === 1 ): ?> 
                <button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"> 
                    <i class="bi bi-plus"></i> Agregar Alergia
                </button>
                <?php endif; ?>
            </div>

            <article id="table-container">
				    <table id="TablaAlergias" class="table table-striped" style="border-radius: 12px;">
                 <thead>
                     <tr>
                         <th class="table-purple">Id</th>
                         <th class="table-purple">Nombre de la Alergia</th>
                         <th class="table-purple">Tipo de Alergia</th>
                         <th class="table-purple">Estado</th>
                         <?php if($_SESSION['usuario']['id_rol'] === 1 ): ?>  
							<th class="table-purple" id="acciones">Acciones</th>
							<?php endif; ?>
                     </tr>
                 </thead>
                 <tbody>
                 </tbody>
             </table>
            </article>

        </main>
    </div>

    <!-- Modal Agregar/Editar Alergia -->
    <div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="TituloModalAlergia" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="TituloModalAlergia">Registrar Alergia</h1>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
         
          <form method="post" class="AlergiasForm" id="formAlergia">
            <div class="modal-body">
               <input type="hidden" id="id_alergia" name="id_alergia">

                <div class="row g-3"> 
                  <div class="col-md-12">
                    <label for="nombre_alergia" class="form-label">Nombre de la Alergia</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Penicilina..." required>
                    <div class="invalid-feedback" id="errorNombre"></div>
                  </div>

                  <div class="col-md-12">
                    <label for="tipo_alergia" class="form-label">Tipo de Alergia</label>
                    <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Ej. Medicamentosa, Alimentaria..." required>
                    <div class="invalid-feedback" id="errorTipo"></div>
                  </div>
                  <div class="col-md-12">
                
                  <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                  <option value="1" selected>Activo</option>
                  <option value="0">Inactivo</option>
                </select>
                <div class="invalid-feedback" id="errorEstado"></div>
              </div>
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

    <script src="public/jquery/jquery-3.7.1.min.js"></script>
    <script src="public/jquery/jquery.validate.min.js"></script>
    <script src="public/datatables/datatables.min.js"></script>
    <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/sweetalert2.min.js"></script>
    <script src="public/js/alerts.js"></script>
    <script src="public/js/ajax.js"></script>
    <script src="public/js/Validate/AlergiaValidation.js"></script>
    <script src="public/js/Alergia.js"></script>
    
</body>
</html>