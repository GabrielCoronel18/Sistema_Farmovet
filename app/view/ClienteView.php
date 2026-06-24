<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">

    
</head>
<body>

   <div class="d-flex">
        
        <?php require_once "componente/menu.php"; ?>

        <main class="main-content">
            
            <header class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-purple mb-0">Clientes</h2>
                    <p class="text-muted">Gestionar Clientes</p>
                </div>

                <div class="dropdown">
                    <button class="btn profile-dropdown-btn d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="text-start d-none d-sm-block" style="line-height: 1.1;">
                            <span class="d-block fw-semibold text-dark" style="font-size: 0.85rem;">Usuario</span>
                            <span class="text-muted" style="font-size: 0.75rem;">Administrador</span>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 10px;">
                        <li><a class="dropdown-menu-item dropdown-item py-2" href="?url=Usuario"><i class="bi bi-person me-2 text-purple"></i>Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="Login"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </header>
            <article class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-plus me-2"></i> Registrar Cliente</button>
            </article>
            <?php require_once "componente/modalCliente.php";?>
            
            <article>
                <table class="table">
                    <thead >
                        <tr>
                            <th class="table-purple">Cedula</th>
                            <th class="table-purple">Nombre</th>
                            <th class="table-purple">Apellido</th>
                            <th class="table-purple">telefono</th>
                            <th class="table-purple">correo</th>
                            <th class="table-purple">direccion</th>
                            <th class="table-purple">Acciones</th>
                        </tr>
                        <tbody>
                            <?php foreach ($datos as $cliente) {
                               echo "                            <tr>
                                <td>". $cliente['cedula_cliente'] ."</td>
                                <td>". $cliente['nombre'] ."</td>
                                <td>". $cliente['apellido'] ."</td>
                                <td>". $cliente['telefono'] ."</td>
                                <td>". $cliente['correo'] ."</td>
                                <td>". $cliente['direccion'] ."</td>

                                <td class='input-group'> <form method='POST' class='m-0 me-2'> <input type='hidden' name='Eliminar' value='1'> <button type='button' class='btn btn-danger btn-eliminar'>Eliminar</button>" ."<input type='hidden' name='valorEliminar' value='". $cliente['cedula_cliente'] ."'></form> <button type='button' class='btn btn-warning btn-modificar' data-cedula='". $cliente['cedula_cliente'] ."' data-nombre='". $cliente['nombre'] ."' data-apellido='". $cliente['apellido'] ."' data-correo='". $cliente['correo'] ."' data-telefono='". $cliente['telefono'] ."' data-direccion='". $cliente['direccion'] ."'>Modificar</button> </td>
                                </tr>" ;
                            }?>
                        </tbody>
                        
                    </thead>
                </table>
            </article>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="public/js/cliente.js"></script>
    <?php if (isset($mensajeAlerta)) { echo "<script>{$mensajeAlerta}</script>"; } ?>
</body>
</html>
       