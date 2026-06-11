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
        
        <nav class="sidebar d-flex flex-column">
            <div class="sidebar-brand">
                <i class="bi bi-heart-pulse-fill me-2"></i>Farmovet
            </div>
            
            <div class="d-flex flex-column w-100 mb-auto">
                
                <a href="?url=Dashboard" class="nav-link-custom ">
                    <div><i class="bi bi-grid-1x2-fill me-2"></i> Inicio</div>
                </a>
                
                <a href="#menuConsultas" data-bs-toggle="collapse" class="nav-link-custom active" aria-expanded="false">
                    <div><i class="bi bi-file-earmark-medical me-2"></i> Consultas</div>
                    <i class="bi bi-chevron-down arrow-icon"></i>
                </a>
                <div class="collapse" id="menuConsultas">
                    <div class="submenu">
                        <a href="?url=NuevaConsulta" class="nav-link-sub">Nueva Consulta</a>
                        <a href="?url=Consulta" class="nav-link-sub">Historial Clinico</a>
                    </div>
                </div>

                <a href="?url=Mascota" class="nav-link-custom">
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
                    <input type="text" class="form-control" placeholder="Filtrar" name="filtrar">
                    </div>
                      <a href="index.php?url=NuevaConsulta" class="btn btn-success" > <i class="bi bi-plus"></i> Agregar Consulta</a>               
                 
              </div>
              <div class="table-responsive shadow-sm rounded">
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
                     <th class="table-purple">Id</th>
                     <th class="table-purple">Mascota</th>
                     <th class="table-purple">Fecha</th>
                     <th class="table-purple">Tipo de Ingreso</th>
                     <th class="table-purple">Remtido</th>
                     <th class="table-purple">Pronostico</th>
                     <th class="table-purple">Tratamiento En Consulta</th>
                     <th class="table-purple">Peso en Consulta</th>
                     <th class="table-purple">Comentario de Seguimiento</th>
                     <th class="table-purple">Detalles de Consulta</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody>
                    <tr class="table-light">
                        <td class="table-light">1</td>
                        <td class="table-light">Logan</td>
                        <td class="table-light">12/02/2026</td>
                        <td class="table-light">1ra vez</td>
                        <td class="table-light td-large">Remitido Por</td>
                        <td class="table-light td-large">Se Pronostica que ...</td>
                        <td class="table-light td-large">Se aplico ...</td>
                        <td class="table-light">10kg</td>
                        <td class="table-light td-large">Comentario...</td>
                        <td class="table-light"> <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#AntecedentesModal">Detalles de Consulta</button></td>
                        <td class="table-light"><a href="index.php?url=NuevaConsulta"class="btn btn-sm btn-success">Editar</a> <button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button></td>
                             
                    </tr>
                    <tr class="table-light">
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>
                        <td class="table-light">...</td>

                    </tr>
                  
                 </tbody>
             </table>
            </div>
            
            </main>
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
    <button class="nav-link active text-purple" id="Anamnesis-tab" data-bs-toggle="tab" data-bs-target="#Anamnesis-tab-pane" type="button" role="tab" aria-controls="Anamnesis-tab-pane" aria-selected="true">Anamnesis</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-purple" id="Examen-Clinico-tab" data-bs-toggle="tab" data-bs-target="#Examen-Clinico-tab-pane" type="button" role="tab" aria-controls="Examen-Clinico-tab-pane" aria-selected="false">Examen Clinico</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-purple" id="Resultados-Laboratorio-tab" data-bs-toggle="tab" data-bs-target="#Resultados-Laboratorio-tab-pane" type="button" role="tab" aria-controls="Resultados-Laboratorio-tab-pane" aria-selected="false">Resultado de Laboratorio</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-purple" id="Diagnostico-tab" data-bs-toggle="tab" data-bs-target="#Diagnostico-tab-pane" type="button" role="tab" aria-controls="Diagnostico-tab-pane" aria-selected="false">Diagnostico</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link text-purple" id="Recipe-tab" data-bs-toggle="tab" data-bs-target="#Recipe-tab-pane" type="button" role="tab" aria-controls="Recipe-tab-pane" aria-selected="false">Recipe</button>
  </li>

</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="Anamnesis-tab-pane" role="tabpanel" aria-labelledby="Anamnesis-tab" tabindex="0">
            <span>Datos de la anamnesis...</span>
            
  </div>
   <div class="tab-pane fade" id="Examen-Clinico-tab-pane" role="tabpanel" aria-labelledby="Examen-Clinico-tab" tabindex="0">
            <span>Datos del examen clinico...</span>
            
  </div>
   <div class="tab-pane fade" id="Resultados-Laboratorio-tab-pane" role="tabpanel" aria-labelledby="Resultados-Laboratorio-tab" tabindex="0">
            <span>Datos de los resultados de laboratorio...</span>
            
  </div>
  <div class="tab-pane fade" id="Diagnostico-tab-pane" role="tabpanel" aria-labelledby="Diagnostico-tab" tabindex="0">
     <div class="d-flex justify-content-end mb-2">
                 <button class="btn btn-success mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDiagnostico" aria-expanded="false" aria-controls="collapseExample">Agregar Diagnostico</button>
            </div>
             
            <div class="collapse" id="collapseDiagnostico">
                    <div class="card card-body">
                       <div class="container-form">
                        <div class="row align-items-end">
                            
                        <div class="col-4">

                            <label for="patologia">Tipo de Diagnostico:</label>
                              <select class="form-select" name="patologia" aria-label="Default select example">
                                <option selected>Seleccione la patologia</option>
                                <option value="1">Patologia...</option>
                                
                                </select>
                                
                            </div>
                            <div class="col-4">

                            <label for="tipo_diagnostico">Tipo de Diagnostico:</label>
                              <select class="form-select" name="tipo_diagnostico" aria-label="Default select example">
                                <option selected>Seleccione el tipo</option>
                                <option value="1">Dx Tentativo</option>
                                <option value="1">Dx Diferenciales</option>
                                <option value="1">Definitivo</option>
                                </select>

                            </div>
                             <div class="col-3">
                                  <button class="btn btn-success btn-agregar" >Agregar</button> 
                            </div>
                            
                        </div>
                        </div>
             
                    </div>
             </div>
             <div class="table-responsive shadow-sm rounded mt-3">
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
                     <th class="table-purple">Patologia</th>
                     <th class="table-purple">Tipo de Diagnostico</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody>
                    <tr class="table-light">
                        <td class="table-light">Patologia ...</td>
                        <td class="table-light">Dx Tentativo</td>
                        <td class="table-light"><button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button></td>
                             
                    </tr>
                    
                 </tbody>
             </table>
            </div>
            
  </div>
  <div class="tab-pane fade" id="Recipe-tab-pane" role="tabpanel" aria-labelledby="Recipe-tab" tabindex="0">
     <div class="d-flex justify-content-end mb-2">
                 <button class="btn btn-success mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecipe" aria-expanded="false" aria-controls="collapseExample">Agregar Recipe</button>
            </div>
             
            <div class="collapse" id="collapseRecipe">
                    <div class="card card-body">
                       <div class="container-form">
                        <div class="row align-items-end">
                            
                           <div class="col-6">
                             
                            <label for="Medicamento">Medicamento:</label>
                              <select class="form-select" name="Medicamento" aria-label="Default select example">
                                <option selected>Seleccione el Medicamento</option>
                                <option value="1">Medicamento...</option>
                                </select>
                            </div>

                            <div class="col-6">
                                  <label for="dosis" class="form-label">Dosis:</label>
                                  <input type="text" class="form-control" name="dosis">
                            </div>
                            <div class="col-6">
                                  <label for="frecuencia" class="form-label">Frecuencia:</label>
                                  <input type="text" class="form-control" name="frecuencia">
                            </div>
                            <div class="col-6">
                                  <label for="duracion" class="form-label">Duracion:</label>
                                  <input type="text" class="form-control" name="duracion">
                            </div>
                            
                             <div class="col-3">
                                  <button class="btn btn-success btn-agregar mt-3" >Agregar</button> 
                            </div>
                            
                        </div>
                        </div>
             
                    </div>

                   
             </div>
              <div class="table-responsive shadow-sm rounded mt-3">
              <table class="table table-striped align-middle text-nowrap ">
                 
              <thead >
                     <th class="table-purple">Medicamento</th>
                     <th class="table-purple">Dosis</th>
                     <th class="table-purple">Frecuencia</th>
                     <th class="table-purple">Duracion</th>
                     <th class="table-purple">Acciones</th>

                 </thead>
                 <tbody>
                    <tr class="table-light">
                        <td class="table-light">Medicamento...</td>
                        <td class="table-light">1 pastilla</td>
                        <td class="table-light">Cada 24 horas</td>
                        <td class="table-light">3 Semanas</td>
                        <td class="table-light"><button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button></td>
                             
                    </tr>
                    
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="public/js/Dashboard.js"></script>

   
</body>
</html>
       