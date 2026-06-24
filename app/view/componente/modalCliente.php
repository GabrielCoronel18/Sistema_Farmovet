<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #5b2c6f;">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
            <form action="" method="post">
                <div class="row gap-2">
                    <div class="col-5">
                        <label for="" class="form-label">Nombre</label>
                        <input type="text" class="form-control" placeholder="ingrese un nombre" name="nombre">
                    </div>
                    <div class="col-5">
                        <label for="" class="form-label">Apellido</label>
                        <input type="text" class="form-control" placeholder="ingrese un apellido" name="apellido">
                    </div>
                    <div class="col-5">
                        <label for="">Cédula</label>
                        <input type="text" class="form-control" placeholder="ingrese una cedula" name="cedula">
                    </div>
                    <div class="col-5">
                        <label for="">Correo Electronico</label>
                        <input type="text" class="form-control"placeholder="ingrese un correo electronico" name="correo">
                    </div>
                    <div class="col-5">
                        <label for="">telefono</label>
                        <input type="text" class="form-control"placeholder="ingrese un telefono" name="telefono">
                    </div>
                    <div class="col-5">
                        <label for="">Dirección</label>
                        <input type="text" class="form-control" placeholder="ingrese una direccion" name="direccion">
                    </div>

                    
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-success" name="enviar">Guardar</button>
            </form>
      </div>
    </div>
  </div>
</div>