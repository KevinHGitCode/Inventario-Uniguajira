
<!-- Modal Crear Bien -->
<div id="modalCrearBienInventario" class="modal">
    <div class="modal-content modal-content-large">
        <span class="close" onclick="ocultarModal('#modalCrearBienInventario')">&times;</span>
        <h2>Nuevo Bien</h2>
        <form id="formCrearBienInventario" autocomplete="off" action="/api/goods-inventory/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="inventarioId" name="inventarioId" />
            <div id="search-container">
                <input type="hidden" id="bien_id" name="bien_id"  />
                <label for="nombreBien">Nombre del bien:</label>
                <input type="text" name="nombre" id="nombreBien" class="form-control" placeholder="Buscar..." required />
                <ul class="suggestions"></ul>
            </div>

            <div class="scrollable-content">
                <div>
                    <label for="cantidadBien">Cantidad:</label>
                    <input type="number" name="cantidad" id="cantidadBien" min="1" />
                </div>

                <div>
                    <label for="descripcionBien">Descripción:</label>
                    <textarea name="descripcion" id="descripcionBien"></textarea>
                </div>

                <div class="inline-fields">
                    <div>
                        <label for="marcaBien">Marca:</label>
                        <input type="text" name="marca" id="marcaBien" />
                    </div>

                    <div>
                        <label for="modeloBien">Modelo:</label>
                        <input type="text" name="modelo" id="modeloBien" />
                    </div>
                </div>

                <div>
                    <label for="serialBien">Serial:</label>
                    <input type="text" name="serial" id="serialBien" />
                </div>

                <div>
                    <label for="estadoBien">Estado:</label>
                    <select name="estado" id="estadoBien">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                        <option value="en mantenimiento">En mantenimiento</option>
                    </select>
                </div>

                <div class="inline-fields">
                    <div>
                        <label for="colorBien">Color:</label>
                        <input type="text" name="color" id="colorBien" />
                    </div>

                    <div>
                        <label for="condicionBien">Condición técnica:</label>
                        <input type="text" name="condicion_tecnica" id="condicionBien" />
                    </div>
                </div>

                <div>
                    <label for="fechaIngresoBien">Fecha de ingreso:</label>
                    <input type="date" name="fecha_ingreso" id="fechaIngresoBien" />
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>
