<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Farmovet - Dashboard</title>
    <link href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="public/css/Dashboard.css">

    
</head>
<body>

   <div class="d-flex">
        
        <?php require_once __DIR__ . '/componente/menu.php'; ?>

          
        </nav>

      <main class="main-content w-100 p-4">
            
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-purple mb-0">Nueva Consulta</h2>        
                    <p class="text-muted">Agregar Nueva Consulta</p>
                </div>

                <?php require_once __DIR__ . '/componente/user.php'; ?>
            </header>

            <div class="container-fluid shadow-sm p-4 bg-white rounded">
                
                <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-md-4">
                        <label for="buscarPaciente" class="form-label">Seleccionar Paciente</label>
                        <select class="form-select" id="buscarPaciente" name="id_mascota">
                            <option selected disabled>Seleccione el Paciente</option>
                            <option value="1">Logan - Juan Pérez</option>
                            <option value="2">Luna - María Gómez</option>
                        </select>
                    </div>
                    
                </div>

               
                    
                    <ul class="nav nav-tabs" id="consultaTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active text-purple fw-semibold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab" aria-controls="general-pane" aria-selected="true">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-purple fw-semibold" id="anamnesis-tab" data-bs-toggle="tab" data-bs-target="#anamnesis-pane" type="button" role="tab" aria-controls="anamnesis-pane" aria-selected="true">Anamnesis</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-purple fw-semibold" id="examen-tab" data-bs-toggle="tab" data-bs-target="#examen-pane" type="button" role="tab" aria-controls="examen-pane" aria-selected="false">Examen Clínico</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-purple fw-semibold" id="laboratorio-tab" data-bs-toggle="tab" data-bs-target="#laboratorio-pane" type="button" role="tab" aria-controls="laboratorio-pane" aria-selected="false">Resultados de Laboratorio</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-4" id="consultaTabsContent">
                        
                        <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab" tabindex="0">
                            <div class="row g-3">
                                <div class="col-3">
                                    <label class="form-label ">Fecha:</label>
                                    <input type="date" class="form-control" name="Fecha" required>
                                </div>
                                <div class="col-2">
                                    <label class="form-label ">Tipo de Ingreso:</label>
                                     <select class="form-select"  name="TipoIngreso">
                                        <option selected disabled>Seleccionar</option>
                                        <option value="1">1ra vez</option>
                                        <option value="2">Sucesivo</option>
                                    </select>
                                    
                                </div>
                                <div class="col-4">
                                    <label class="form-label ">Remitido:</label>
                                    <input type="text" class="form-control" name="Remitido" required>
                                </div>
                                <div class="col-9">
                                    <label class="form-label ">Pronostico:</label>
                                    <input type="text" class="form-control" name="Pronostico">
                                </div>
                                <div class="col-9">
                                    <label class="form-label ">Tratamiento en Consulta:</label>
                                    <input type="text" class="form-control" name="Tratamiento-consulta">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Peso en Tratamiento(Kg):</label>
                                    <input type="number" class="form-control" name="peso_consulta">
                                </div>
                                <div class="col-12">
                                    <label class="form-label ">Comentario Clinico y/o Seguimiento:</label>
                                    <textarea  class="form-control" name="Comentario"></textarea>
                                </div>
                            </div>
                        </div>

                    
                        <div class="tab-pane fade" id="anamnesis-pane" role="tabpanel" aria-labelledby="anamnesis-tab" tabindex="0">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Motivo de Consulta:</label>
                                    <textarea type="text" class="form-control" name="motivo_consulta" required></textarea>
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Incio de la Enfermedad:</label>
                                    <input type="date" class="form-control" name="Inicio_Enfermedad" required>
                                    
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Examenes efectuados:</label>
                                    <textarea class="form-control" name="examenes_efectuados"></textarea>
                                </div>
                            
                              
                            <div class="col-6">
                                    <label class="form-label">Tratamientos Realizados:</label>
                                    <textarea class="form-control" name="Tratamientos_Realizados"></textarea>
                                </div>
                               <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Ultimas Desparasitacion</h5>
                                <div class="col-4">
                                     <label class="form-label ">Fecha Desparasitacion Intera:</label>
                                    <input type="date" class="form-control" name="Ult_desp_interna" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Producto Desparasitacion Interna:</label>
                                    <input type="text" class="form-control" name="Ult_desp_interna_prod" required>
                                    
                                </div>
                                
                                <div class="col-4">
                                     <label class="form-label ">Fecha Desparasitacion Externa:</label>
                                    <input type="date" class="form-control" name="Ult_desp_externa" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Producto Desparasitacion Externa:</label>
                                    <input type="text" class="form-control" name="Ult_desp_interna_prod" required>
                                    
                                </div>
                                
                                <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Ultima Vacunacion</h5>

                                <div class="col-4">
                                     <label class="form-label ">Fecha de Vacunacion:</label>
                                    <input type="date" class="form-control" name="Ult_Vacunacion" required>
                                    
                                </div>
                                
                                <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Alimentacion y Habitos</h5>

                                <div class="col-6">
                                     <label class="form-label ">Tipo de Alimentacion:</label>
                                    <input type="text" class="form-control" name="Tipo_alimentacion" required>
                                    
                                </div>
                                 <div class="col-6">
                                     <label class="form-label ">Frecuencia de Alimentacion:</label>
                                    <input type="text" class="form-control" name="Frecuencia_alimentacion" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Apetito:</label>
                                    <input type="text" class="form-control" name="Apetito" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Ingesta de Agua:</label>
                                    <input type="text" class="form-control" name="Ingesta_Agua" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Vomito:</label>
                                    <input type="text" class="form-control" name="Vomito" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Heces:</label>
                                    <input type="text" class="form-control" name="Heces" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Miccion:</label>
                                    <input type="text" class="form-control" name="Miccion" required>
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Contacto Animal:</label>
                                    <input type="text" class="form-control" name="Contacto:_animal" required>
                                    
                                </div>
                                <div class="col-4">
                                     <label class="form-label ">Fecha de Ultimo Celo:</label>
                                    <input type="date" class="form-control" name="Ult_Celo" required>
                                </div>

                                 <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Higiene</h5>
                                <div class="col-6">
                                     <label class="form-label ">Producto Higiene:</label>
                                    <input type="text" class="form-control" name="Producto_Higiene" required>
                                    </div>
                                 <div class="col-6">
                                     <label class="form-label ">Frecuencia Higiene:</label>
                                    <input type="text" class="form-control" name="Frecuencia_Higiene" required>
                                    </div>

                                 <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Ambiente</h5>
                                <div class="col-6">
                                     <label class="form-label ">Ambiente Interno:</label>
                                    <input type="text" class="form-control" name="Ambiente_Interno" required>
                                    </div>
                                 <div class="col-6">
                                     <label class="form-label ">Ambiente Externo:</label>
                                    <input type="text" class="form-control" name="Ambiente_Externo" required>
                                    </div>
                                     
                                    
                                    <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Ectoparasitos</h5>
                                <div class="col-3">
                                     <label class="form-label ">Ha Presentado Ectoparasitos:</label>
                                     <select class="form-select"  name="Ant_ectoparasitos">
                                        <option selected disabled>Seleccionar</option>
                                        <option value="1">Si</option>
                                        <option value="2">No</option>
                                    </select>
                                    </div>
                                 <div class="col-3">
                                     <label class="form-label ">Actualmente Presenta Ectoparasitos:</label>
                                     <select class="form-select" name="Act_ectoparasitos">
                                        <option selected disabled>Seleccionar</option>
                                        <option value="1">Si</option>
                                        <option value="2">No</option>
                                    </select>
                                    </div>
                            </div>
                        </div>
                          
                        <div class="tab-pane fade" id="examen-pane" role="tabpanel" aria-labelledby="examen-tab" tabindex="0">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-2">
                                    <label class="form-label">Peso(Kg):</label>
                                    <input type="number" class="form-control" name="Peso">
                                </div>
                                 <div class="col-2">
                                    <label class="form-label">C.C(1-9):</label>
                                    <input type="number" class="form-control" name="CC">
                                </div>
                                <div class="col-2">
                                    <label class="form-label">Temperatura °C:</label>
                                    <input type="number" class="form-control" name="Temp_Celsius">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Pulso Yugular:</label>
                                    <input type="text" class="form-control" name="Pulso_yugular">
                                </div>
                                <div class="col-2">
                                    <label class="form-label">FR(rpm):</label>
                                    <input type="number" class="form-control" name="Fr_rpm">
                                </div>
                                <div class="col-2">
                                    <label class="form-label">FC(lpm):</label>
                                    <input type="number" class="form-control" name="Fc_lpm">
                                </div>
                                <div class="col-2">
                                    <label class="form-label">Pulso(ppm):</label>
                                    <input type="number" class="form-control" name="Pulso_ppm">
                                </div>
                                 <div class="col-2">
                                    <label class="form-label">TLC(seg):</label>
                                    <input type="number" class="form-control" name="Tlc_seg">
                                </div>
                                <div class="col-2">
                                    <label class="form-label">TPC(seg):</label>
                                    <input type="number" class="form-control" name="Tpc_seg">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">PAS:</label>
                                    <input type="text" class="form-control" name="Pas">
                                    
                                </div>
                                <div class="col-3">
                                    <label class="form-label">PAD:</label>
                                    <input type="text" class="form-control" name="Pad">
                                </div>
                                 <div class="col-2">
                                    <label class="form-label">% de Deshidratacion:</label>
                                    <input type="number" class="form-control" name="Prcnt_deshidratacion">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Ganglios Palpables:</label>
                                    <input type="text" class="form-control" name="Ganglios_palpables">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Mucosas Visibles:</label>
                                    <input type="text" class="form-control" name="Mucosas_visibles">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Ectoparasitos:</label>
                                    <input type="text" class="form-control" name="Ectoparasitos">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Actitud:</label>
                                    <input type="text" class="form-control" name="Actitud">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Hallazgos:</label>
                                    <textarea class="form-control" name="Hallazgos"></textarea>
                                </div>
                                
                            </div>
                        </div>

                        <div class="tab-pane fade" id="laboratorio-pane" role="tabpanel" aria-labelledby="laboratorio-tab" tabindex="0">
                            <div class="row g-3">
                                
                                <div class="col-12">
                                     <label class="form-label ">Hematologia:</label>
                                    <textarea class="form-control" name="Hematologia" ></textarea>
                                    
                                </div>

                                   <div class="col-12">
                                     <label class="form-label ">Coprologia:</label>
                                    <textarea class="form-control" name="Coprologia" ></textarea>
                                    
                                </div>

                                   <div class="col-12">
                                     <label class="form-label ">Quimica Sanguinea:</label>
                                    <textarea class="form-control" name="Quimica_sanguinea" ></textarea>
                                    
                                </div>

                                <h5 class="text-secondary mt-3 mb-3 border-bottom pb-2">Uroanalisis</h5>
                                 
                                <div class="col-3">
                                     <label class="form-label ">Sangre:</label>
                                    <input type="text" class="form-control" name="Uro_sangre" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Urob:</label>
                                    <input type="text" class="form-control" name="Uro_urob" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Bli:</label>
                                    <input type="text" class="form-control" name="Uro_bli" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Prot:</label>
                                    <input type="text" class="form-control" name="Uro_prot" >
                                    
                                </div>
                                 
                                <div class="col-3">
                                     <label class="form-label ">Nitritos:</label>
                                    <input type="text" class="form-control" name="Uro_nitritos" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Cetona:</label>
                                    <input type="text" class="form-control" name="Uro_cetona" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Glucosa:</label>
                                    <input type="text" class="form-control" name="Uro_glucosa" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Ph:</label>
                                    <input type="text" class="form-control" name="Uro_ph" >
                                    
                                </div>  
                                <div class="col-3">
                                     <label class="form-label ">Leu:</label>
                                    <input type="text" class="form-control" name="Uro_leu" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Densidad:</label>
                                    <input type="text" class="form-control" name="Uro_densidad" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Microorganismos:</label>
                                    <input type="text" class="form-control" name="Uro_microorganismos" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Celulas:</label>
                                    <input type="text" class="form-control" name="Uro_celulas" >
                                    
                                </div>  
                                <div class="col-3">
                                     <label class="form-label ">Cilindros:</label>
                                    <input type="text" class="form-control" name="Uro_cilindros" >
                                    
                                </div>
                                <div class="col-3">
                                     <label class="form-label ">Cristales:</label>
                                    <input type="text" class="form-control" name="Uro_cristales" >
                                    
                                </div>
                                
                                <span class="border-bottom mt-5"></span>
                                
                               <div class="col-6">
                                     <label class="form-label ">Descarte:</label>
                                    <input type="text" class="form-control" name="Descarte" >
                                    
                                </div>
                                <div class="col-6">
                                     <label class="form-label ">Piel u otros:</label>
                                    <input type="text" class="form-control" name="Piel-otros" >
                                    
                                </div>
                                    
                               <div class="col-6">
                                     <label class="form-label ">Descarte:</label>
                                    <input type="text" class="form-control" name="Descarte" >
                                    
                                </div>
                                    
                               <div class="col-6">
                                     <label class="form-label ">SNAP:</label>
                                    <input type="text" class="form-control" name="snap" >
                                    
                                </div>
                                <div class="col-12">
                                     <label class="form-label ">Observaciones:</label>
                                    <textarea class="form-control" name="Observaciones" ></textarea>
                                    
                                </div>
                        </div>
                    </div>
                     <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                        <button type="button" class="btn btn-secondary me-2">Cancelar</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold btn-agregar">Guardar </button>
                    </div>

                </form>
            </div>
            </main>

           
    </div>

     <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="public/js/Dashboard.js"></script>
</body>
</html>