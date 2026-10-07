<!-- Modal Especies -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Especie</h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" class="EspeciesForm">
        <input type="hidden" id="is_update" name="is_update" value="0">
        <input type="hidden" id="id_especie" name="id_especie" value="">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="nombre_especie" class="form-label">Nombre de la Especie</label>
              <input type="text" class="form-control" id="nombre_especie" name="nombre_especie" placeholder="Ej. Canino, Felino, Ave..." required>
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