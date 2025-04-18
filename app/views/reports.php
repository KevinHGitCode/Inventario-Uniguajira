<div class="container">
  <h2>Generar Reporte de Bienes</h2>

  <!-- Filtros -->
  <form id="form-filtros-reportes" class="card p-3 mb-4">
    <div class="row mb-3">
      <!-- Sede o Edificio -->
      <div class="col-md-4">
        <label for="sede">Sede:</label>
        <select id="sede" name="sede" class="form-control">
          <option value="">Todas</option>
          <!-- opciones dinámicas -->
        </select>
      </div>

      <!-- Ambiente -->
      <div class="col-md-4">
        <label for="ambiente">Ambiente:</label>
        <select id="ambiente" name="ambiente" class="form-control">
          <option value="">Todos</option>
          <!-- opciones según sede -->
        </select>
      </div>

      <!-- Tipo de bien (opcional) -->
      <div class="col-md-4">
        <label for="tipo_bien">Tipo de bien:</label>
        <select id="tipo_bien" name="tipo_bien" class="form-control">
          <option value="">Todos</option>
          <option value="Cantidad">Por cantidad</option>
          <option value="Serial">Por serial</option>
        </select>
      </div>
    </div>

    <!-- Botón para cargar bienes filtrados -->
    <button type="submit" class="btn btn-primary">Buscar bienes</button>
  </form>

  <!-- Tabla de bienes seleccionables -->
  <div id="tabla-bienes" class="table-responsive d-none">
    <form id="form-generar-reporte">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th><input type="checkbox" id="check-todos" /></th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Ambiente</th>
            <th>Sede</th>
          </tr>
        </thead>
        <tbody>
          <!-- filas dinámicas con checkbox -->
        </tbody>
      </table>
      <button type="submit" class="btn btn-success">Generar PDF</button>
    </form>
  </div>
</div>
