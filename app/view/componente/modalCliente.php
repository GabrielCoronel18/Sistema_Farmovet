<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header ">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Cliente</h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" class="ClienteForm">
      <input type="hidden" id="is_update" value="0">
      <div class="modal-body">
          <div class="row g-3"> 
            <div class="col-md-6">
              <label for="cedula_cliente" class="form-label">Cédula</label>
              <input type="text" class="form-control" id="cedula_cliente" name="cedula_cliente" required>
            </div>
            <div class="col-md-6">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label for="apellido" class="form-label">Apellido</label>
              <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="col-md-6">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="col-12">
              <label for="correo" class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="col-12">
              <label for="direccion" class="form-label">Dirección</label>
              <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
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