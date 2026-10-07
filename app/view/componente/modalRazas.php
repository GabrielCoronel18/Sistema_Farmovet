<!-- Modal Razas -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Raza</h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form method="post" class="RazaForm" id="RazaForm">
        <input type="hidden" id="is_update" name="is_update" value="0">
        <input type="hidden" id="id_raza" name="id_raza" value="">

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label for="nombre_raza" class="form-label">Nombre de la Raza</label>
              <input type="text" class="form-control" id="nombre_raza" name="nombre_raza" placeholder="Ej: Poodle, Labrador, Persa" required>
            </div>

            <div class="col-md-12">
              <label for="id_especie" class="form-label">Especie</label>
              <select class="form-select" id="id_especie" name="id_especie" required>
                <option value="" selected disabled>Seleccione una especie</option>
                <?php if (isset($especies) && !empty($especies)): ?>
                  <?php foreach ($especies as $especie): ?>
                    <option value="<?= $especie['id_especie']; ?>">
                      <?= $especie['nombre_especie']; ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
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