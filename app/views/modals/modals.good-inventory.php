<!-- Modal Crear Bien -->
<div id="modalCrearBienInventario" class="modal">
    <div class="modal-content modal-content-large">
        <span class="close" onclick="ocultarModal('#modalCrearBienInventario')">&times;</span>
        <h2>Nuevo Bien</h2>
        <form id="formCrearBienInventario" autocomplete="off" action="/api/goods-inventory/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="inventarioId" name="inventarioId" />
            <div id="search-container" class="form-group">
                <input type="hidden" id="bien_id" name="bien_id" />
                <label for="nombreBien">Nombre del bien:</label>
                <input type="text" name="nombre" id="nombreBien" class="form-control" placeholder="Buscar..." required />
                <ul class="suggestions"></ul>
            </div>

            <!-- Estos campos se mostrarán dinámicamente según el tipo de bien -->
            <div class="scrollable-content" id="dynamicFields" style="display: none;">
                <!-- Campo para bienes de tipo Cantidad -->
                <div id="camposCantidad" style="display: none;">
                    <div class="form-group">
                        <label for="cantidadBien">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidadBien" min="1" value="1" class="form-control" />
                    </div>
                </div>

                <!-- Campos para bienes de tipo Serial -->
                <div id="camposSerial" style="display: none;">
                    <div class="form-group">
                        <label for="descripcionBien">Descripción:</label>
                        <textarea name="descripcion" id="descripcionBien" class="form-control"></textarea>
                    </div>

                    <div class="inline-fields">
                        <div class="form-group">
                            <label for="marcaBien">Marca:</label>
                            <input type="text" name="marca" id="marcaBien" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="modeloBien">Modelo:</label>
                            <input type="text" name="modelo" id="modeloBien" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="serialBien">Serial:</label>
                        <input type="text" name="serial" id="serialBien" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="estadoBien">Estado:</label>
                        <select name="estado" id="estadoBien" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="en mantenimiento">En mantenimiento</option>
                        </select>
                    </div>

                    <div class="inline-fields">
                        <div class="form-group">
                            <label for="colorBien">Color:</label>
                            <input type="text" name="color" id="colorBien" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="condicionBien">Condición técnica:</label>
                            <input type="text" name="condicion_tecnica" id="condicionBien" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fechaIngresoBien">Fecha de ingreso:</label>
                        <input type="date" name="fecha_ingreso" id="fechaIngresoBien" class="form-control" />
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>