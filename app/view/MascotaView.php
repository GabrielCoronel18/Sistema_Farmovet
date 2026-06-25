<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Dashboard</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">

    
</head>
<body>

   <div class="d-flex">
        
        <nav class="sidebar d-flex flex-column">
            <div class="sidebar-brand">
                <i class="bi bi-heart-pulse-fill me-2"></i>Farmovet
            </div>
            
            <div class="d-flex flex-column w-100 mb-auto">
                
                <a href="?url=Dashboard" class="nav-link-custom ">
                    <div><i class="bi bi-grid-1x2-fill me-2"></i> Inicio</div>
                </a>
                
                <a href="#menuConsultas" data-bs-toggle="collapse" class="nav-link-custom " aria-expanded="false">
                    <div><i class="bi bi-file-earmark-medical me-2"></i> Consultas</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse" id="menuConsultas">
                    <div class="submenu">
                        <a href="?url=NuevaConsulta" class="nav-link-sub">Nueva Consulta</a>
                        <a href="?url=Consulta" class="nav-link-sub">Historial Clinico</a>
                    </div>
                </div>

                <a href="?url=Mascota" class="nav-link-custom active">
                    <div><i class="fa-solid fa-paw me-2"></i> Mascotas</div>
                </a>
                
                <a href="?url=Cliente" class="nav-link-custom">
                    <div><i class="bi bi-people-fill me-2"></i> Clientes</div>
                </a>
                
                <a href="?url=agente" class="nav-link-custom">
                    <div><i class="bi bi-shield-plus me-2"></i> Planes Sanitarios</div>
                </a>

                <a href="#menuConfiguracion" data-bs-toggle="collapse" class="nav-link-custom" aria-expanded="false">
                    <div><i class="bi bi-gear-fill me-2"></i></i> Configuracion</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse" id="menuConfiguracion">
                    <div class="submenu">
                        <a href="?url=Razas" class="nav-link-sub">Razas</a>
                        <a href="?url=Especies" class="nav-link-sub">Especies</a>
                        <a href="?url=Medicamento" class="nav-link-sub">Medicamentos</a>
                        <a href="?url=TipoMedicamento" class="nav-link-sub">Tipos de Medicamento</a>
                        <a href="?url=Alergia" class="nav-link-sub">Alergias</a>
                        <a href="?url=Patologia" class="nav-link-sub">Patologias</a>
                    </div>
                </div>

              <a href="#menuSeguridad" data-bs-toggle="collapse" class="nav-link-custom" aria-expanded="false">
                    <div><i class="bi bi-shield-lock-fill me-2"></i> Seguridad</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse" id="menuSeguridad">
                    <div class="submenu">
                        <a href="?url=Usuario" class="nav-link-sub">Usuarios</a>
                        <a href="?url=Rol" class="nav-link-sub">Roles</a>
                    </div>
                </div>

            </div>

          
        </nav>

        <main class="main-content">
            
            <header class="d-flex  justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-purple mb-0">Mascotas</h2>
                     <p class="text-muted">Gestion de Mascotas</p>
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
              <div class=" d-flex justify-content-end mb-3">
                    <div class="me-3">
                    <input type="text" class="form-control" placeholder="Filtrar" name="filtrar" id="filtrar">   
                    </div>     
                              
                    <button type="button" class="btn btn-success" id="btnAgregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"> <i class="bi bi-plus"></i> Agregar Mascota</button>

              </div>
              <div class="table-responsive shadow-sm rounded">
              
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
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
                     <th class="table-purple">Antecedentes</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody id="TablaMascotas">
               
                
                  
                 </tbody>
             </table>
            </div>
            
            </main>
    </div>
    


<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header ">
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
              <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
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
              <label for="raza" class="form-label">Raza</label>
              <select class="form-select" id="id_raza" name="id_raza" required>
                <option value="" selected disabled>Seleccione una raza</option>
                <option value="1">Pastor Alemán</option> 
              </select>
            </div>

             <div class="col-md-6">
              <label for="pelaje class="form-label">Pelaje</label>
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
              <textarea class="form-control" id="procedencia" name="procedencia" rows="2" placeholder=""></textarea>
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


<div class="modal fade" id="AntecedentesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header ">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Antecedentes</h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active text-purple" id="Alergias-tab" data-bs-toggle="tab" data-bs-target="#Alergias-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Alergias</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-purple" id="Cirugias-tab" data-bs-toggle="tab" data-bs-target="#Cirugias-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Cirugias Previas</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-purple" id="Enfermedades-tab" data-bs-toggle="tab" data-bs-target="#Enfermedades-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Enfermedades Sufridas</button>
  </li>

</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="Alergias-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            
            <div class="d-flex justify-content-end mb-2">
                 <button class="btn btn-success mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAlergia" aria-expanded="false" aria-controls="collapseExample">Agregar Alergia</button>
            </div>
             
            <div class="collapse" id="collapseAlergia">
                    <div class="card card-body">
                       <div class="container-form">
                        <form method="post" id="AlergiasForm">
                       <div class="row align-items-end">
                            <div class="col-4">
                             
                            <label for="alergias">Alergia:</label>
                              <select class="form-select" name="alergias" id="alergias" aria-label="Default select example">
                                <option selected>Seleccione la alergia</option>
                                <option value="1">Penicilina</option>

                                </select>
                            </div>
                            <div class="col-4">
                                  <label for="fecha-deteccion" class="form-label">Fecha de Deteccion:</label>
                                  <input type="date" class="form-control" name="fecha-deteccion" id="fecha-deteccion">
                            </div>
                             <div class="col-3">
                                  <button type="submit" class="btn btn-success btn-agregar" >Agregar</button> 
                                  </form>
                            </div>
                            
                        </div>
                        </div>
             
                    </div>
             </div>
             <div class="table-responsive shadow-sm rounded mt-3">
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
                     <th class="table-purple">Alergia</th>
                     <th class="table-purple">Fecha de Deteccion</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody id="TablaAlergias">
                   
            
                 </tbody>
             </table>
            </div>
            
  </div>
  <div class="tab-pane fade" id="Cirugias-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
     <div class="d-flex justify-content-end mb-2">
                 <button class="btn btn-success mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCirugia" aria-expanded="false" aria-controls="collapseExample">Agregar Cirugia</button>
            </div>
             
            <div class="collapse" id="collapseCirugia">
                    <div class="card card-body">
                       <div class="container-form">
                        <form method="post" id="CirugiasForm">
                        <div class="row align-items-end">
                            <div class="col-4">
                                <label for="cirugia" class="form-label">Cirugia:</label>
                                <input type="textarea" class="form-control" name="cirugia">
                            </div>
                            <div class="col-4">
                                  <label for="fecha-cirugia" class="form-label">Fecha:</label>
                                  <input type="date" class="form-control" name="fecha_cirugia">
                            </div>
                             <div class="col-3">
                                  <button type="submit" class="btn btn-success btn-agregar" >Agregar</button> 
                            </div>
                            
                          </div>
                        </form>
                        </div>
             
                    </div>
             </div>
             <div class="table-responsive shadow-sm rounded mt-3">
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
                     <th class="table-purple">Cirugia</th>
                     <th class="table-purple">Fecha</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody id="TablaCirugias">
                    
                    
                 </tbody>
             </table>
            </div>
            
  </div>
  <div class="tab-pane fade" id="Enfermedades-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
     <div class="d-flex justify-content-end mb-2">
                 <button class="btn btn-success mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnfermedades" aria-expanded="false" aria-controls="collapseExample">Agregar Enfermedad</button>
            </div>
             
            <div class="collapse" id="collapseEnfermedades">
                    <div class="card card-body">
                       <div class="container-form">
                        <form method="post" id="EnfermedadesForm">
                        <div class="row align-items-end">
                            
                           <div class="col-3">
                             
                            <label for="enfermedad">Enfermedad:</label>
                              <select class="form-select" name="enfermedad" aria-label="Default select example">
                                <option selected>Seleccione la enfermededad</option>
                                <option value="1">Enfermedad...</option>
                                </select>
                            </div>

                            <div class="col-3">
                                  <label for="fecha-deteccion" class="form-label">Fecha de Deteccion:</label>
                                  <input type="date" class="form-control" name="fecha_deteccion">
                            </div>
                            <div class="col-3">  
                            <label for="estado-enfermedad">Estado:</label>
                              <select class="form-select" name="estado_enfermedad" aria-label="Default select example">
                                <option selected>Seleccione la enfermededad</option>
                             
                                <option value="Leve">Leve</option>
                                <option value="Grave">Grave</option>
                                <option value="Muy Grave">Muy Grave</option>
                                </select>
                            </div>
                             <div class="col-3">
                                  <button type="submit" class="btn btn-success btn-agregar" >Agregar</button> 
                            </div>
                            
                        </div>
                        </form>
                        </div>
             
                    </div>

                   
             </div>
              <div class="table-responsive shadow-sm rounded mt-3">
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
                     <th class="table-purple">Enfermedad</th>
                     <th class="table-purple">Fecha-diagnostico</th>
                     <th class="table-purple">Estado</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody id="TablaEnfermedades">
                    
                    
                 </tbody>
             </table>
            </div>
             
            
  </div>
 
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>


    <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="public/js/sweetalert2.min.js"></script>
    
    <script src="public/js/alerts.js"></script>

    <script src="public/js/Mascotas.js"></script>

    <script src="public/js/Antecedentes.js"></script>
    
</body>
</html>
       