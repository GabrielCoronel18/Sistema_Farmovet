<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Dashboard</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">
</head>
<body>

   <div class="d-flex">
        <?php require_once __DIR__ . '/componente/menu.php'; ?>

        <main class="main-content">
            <header class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-purple mb-0">Mascotas</h2>
                    <p class="text-muted">Gestion de Mascotas</p>
                </div>

                <?php require_once __DIR__ . '/componente/user.php'; ?>
            </header>

            <div class="d-flex justify-content-end mb-3">
                <div class="me-3">
                    <input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar">   
                </div>     
                <button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"> 
                    <i class="bi bi-plus"></i> Agregar Mascota
                </button>
            </div>

            <div class="table-responsive shadow-sm rounded">
              <table class="table table-striped align-middle text-nowrap">
                 <thead>
                     <th class="table-purple">Id</th>
                     <th class="table-purple">Nombre</th>
                     <th class="table-purple">Edad</th>
                     <th class="table-purple">Sexo</th>
                     <th class="table-purple">Chip</th>
                     <th class="table-purple">Procedencia</th>
                     <th class="table-purple">Fecha-Nacimiento</th>
                     <th class="table-purple">Raza</th>
                     <th class="table-purple">Pelaje</th>
                     <th class="table-purple">Cliente</th>
                     <th class="table-purple">Alergias</th>
                     <th class="table-purple">Enfermedades</th>
                     <th class="table-purple">Cirugías</th>
                     <th class="table-purple">Acciones</th>
                 </thead>
                 <tbody id="TablaMascotas">
                 </tbody>
             </table>
            </div>
        </main>
    </div>

<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="TituloModalMascotas"></h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <form method="post" class="MascotasForm">
      <div class="modal-body">
         <input type="hidden" id="id_mascota" name="id_mascota">

          <div class="row g-3"> 
            <div class="col-md-6">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="col-md-6">
              <label for="edad" class="form-label">Edad</label>
              <input type="number" class="form-control" id="edad" name="edad" required>
            </div>
            
            <div class="col-md-6">
              <label for="fch_nacimiento" class="form-label">Fecha de Nacimiento</label>
              <input type="date" class="form-control" id="fch_nacimiento" name="fch_nacimiento" required>
            </div>

            <div class="col-md-6">
              <label for="sexo" class="form-label">Sexo</label>
              <select class="form-select" id="sexo" name="sexo" required>
                <option value="" selected disabled>Seleccione...</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="chip" class="form-label">Chip</label>
              <select class="form-select" id="chip" name="chip" required>
                <option value="" selected disabled>Seleccione...</option>
                <option value="Si">Si</option>
                <option value="No">No</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="id_raza" class="form-label">Raza</label>
              <select class="form-select" id="id_raza" name="id_raza" required>
                <option value="" selected disabled>Seleccione una raza</option>
                <option value="1">Pastor Alemán</option> 
              </select>
            </div>

             <div class="col-md-6">
              <label for="pelaje" class="form-label">Pelaje</label>
              <input type="text" class="form-control" id="pelaje" name="pelaje" required>
            </div>

            <div class="col-md-6">
              <label for="cedula_cliente" class="form-label">Cliente</label>
              <select class="form-select" id="cedula_cliente" name="cedula_cliente" required>
                <option value="" selected disabled>Seleccione un cliente</option>
                <option value="12313122">Jaime</option>
              </select>
            </div>

            <div class="col-12">
              <label for="procedencia" class="form-label">Procedencia</label>
              <textarea class="form-control" id="procedencia" name="procedencia" rows="2"></textarea>
            </div>

            <hr class="my-4">
            <h6 class="fw-bold text-purple"><i class="bi bi-journal-medical me-2"></i>Antecedentes Médicos (Opcional)</h6>

           <div class="col-md-12">
              <label for="mascota_alergias" class="form-label">Alergias</label>
              <select class="form-select" id="mascota_alergias" name="alergias[]" multiple>
                <?php foreach ($alergiasDisponibles as $alergia): ?>
                  <option value="<?= (int) $alergia["id_alergia"] ?>"><?= htmlspecialchars($alergia["nombre_alergia"]) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-12">
              <label for="mascota_enfermedades" class="form-label">Enfermedades</label>
              <select class="form-select" id="mascota_enfermedades" name="enfermedades[]" multiple>
                <?php foreach ($enfermedadesDisponibles as $enfermedad): ?>
                  <option value="<?= (int) $enfermedad["id_patologia"] ?>"><?= htmlspecialchars($enfermedad["nombre"]) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-12">
              <label for="mascota_cirugias" class="form-label">Cirugías</label>
              <select class="form-select" id="mascota_cirugias" name="cirugias[]" multiple>
                <?php foreach ($cirugiasDisponibles as $cirugia): ?>
                  <option value="<?= (int) $cirugia["id_cirugia"] ?>"><?= htmlspecialchars($cirugia["nombre_cirugia"]) ?></option>
                <?php endforeach; ?>
              </select>
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

    <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script src="public/js/sweetalert2.min.js"></script>
    <script src="public/js/alerts.js"></script>
    <script src="public/js/Mascotas.js"></script>
</body>
</html>