<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/Dashboard.css">
    <link href="https://cdn.datatables.net/v/dt/dt-3.0.3/datatables.min.css" rel="stylesheet" integrity="sha384-qWEvqSybGvARiE5fKMA75H4edwdEGqOXm8GFt12T7kp5MOlvOxrbnpNkv9bB+XAB" crossorigin="anonymous">

</head>
<style>
    #table-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }

    .table-container table.dataTable {
        border: none !important;
        margin: 0 !important;
    }
</style>
<body>

    <div class="d-flex">

        <!-- 1. AQUÍ SE AGREGA LA DEL MENÚ -->
        <?php require_once "app/view/componente/menu.php"; ?>

        <main class="main-content">

            <header class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-purple mb-0">Especies</h2>
                    <p class="text-muted">Gestionar Especies</p>
                </div>

                <?php require_once "user.php"; ?>

            </header>
            
            <article class="d-flex justify-content-end">
                <?php if($_SESSION['usuario']['id_rol'] === 1 ): ?> 
              <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal" id="btnAgregar"> 
                    <i class="bi bi-plus me-2"></i> Registrar Especie
                </button>
                <?php endif; ?>
            </article>
            
            <!-- 2. AQUÍ SE AGREGA LA DEL MODAL -->
            <?php require_once "app/view/componente/modalEspecies.php"; ?>    

            <article id="table-container">
                <table class="table table-striped" id="TablaEspecies" style="border-radius: 12px;">
                    <thead>
                        <tr>
                            <th class="table-purple">ID</th>
                            <th class="table-purple">Nombre de la Especie</th>
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

    <!-- jQuery principal sin candados de integrity -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/v/dt/dt-3.0.3/datatables.min.js" integrity="sha384-1rFATsUr0XuIonb0gHkZHfQsWDvjIDbM3VLnsBS+sNTHWo71J7FkrBG2n93tytfI" crossorigin="anonymous"></script>

    <script src="public/js/sweetalert2.min.js"></script>

    <script src="public/js/alerts.js"></script>

    <!-- El plugin de validación -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    
        <!-- 1. AQUÍ PRESENTAMOS EL ARCHIVO AJAX (NUEVO) -->
    <script src="public/js/ajax.js"></script>


    <script src="public/js/Validate/EspeciesValidation.js"></script>


    <script src="public/js/especies.js"></script>
    
    <?php if (isset($mensajeAlerta)) {
        echo "<script>{$mensajeAlerta}</script>";
    } ?>
</body>

</html>