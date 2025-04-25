
<!-- Modal Crear Grupo -->
<div id="modalCrearGrupo" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCrearGrupo')">&times;</span>
        <h2>Nuevo Grupo</h2>
        <form
            id="formCrearGrupo"
            action="/api/groups/create"
            method="POST"
            autocomplete="off"
        >
            <div>
                <label for="nombreGrupo">Nombre del grupo:</label>
                <input type="text" name="nombre" id="nombreGrupo" required />
            </div>
            <div style="margin-top: 10px">
                <button 
                    type="submit" 
                    id="create-btn-grupo" 
                    class="create-btn">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ---------------------------------------------------------------------- -->
<!-- Modal Renombrar Grupo -->
<div id="modalRenombrarGrupo" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalRenombrarGrupo" class="close" onclick="ocultarModal('#modalRenombrarGrupo')">&times;</span>
        <h2>Renombrar Grupo</h2>
        <form 
            id="formRenombrarGrupo" 
            action="/api/groups/rename"
            autocomplete="off"
        >
            <input type="hidden" name="id" id="grupoRenombrarId" />

            <div>
                <label for="grupoRenombrarNombre">Nuevo Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    id="grupoRenombrarNombre"
                    required
                />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- --------------------------------------------------------------- -->


<!-- Modal Crear Inventario -->
<div id="modalCrearInventario" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCrearInventario')">&times;</span>
        <h2>Nuevo Inventario</h2>
        <form 
            id="formCrearInventario" 
            action="/api/inventories/create" 
            method="POST"
            autocomplete="off"
        >
            <!-- TODO: este input tiene riesgo de quedar vacio si la creacion se vuelve dinamica y no llama a loadContend -->
            <input type="hidden" name="grupo_id" value="<?= $dataIdGroup ?>" required />
            <div>
                <label for="nombreInventario">Nombre del inventario:</label>
                <input type="text" name="nombre" id="nombreInventario" required />
            </div>

            <div style="margin-top: 10px">
                <button 
                id="btnCrearInventario"
                type="submit" 
                class="create-btn">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ---------------------------------------------------------------------- -->

<!-- Modal renombrar Inventario -->
<div id="modalRenombrarInventario" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalRenombrarInventario')">&times;</span>
        <h2>Renombrar Inventario</h2>
        <form 
            id="formRenombrarInventario"
            action="/api/inventories/rename"
            method="POST"
            autocomplete="off"
        >
            <input type="hidden" name="inventory_id" id="renombrarInventarioId" />
            <div>
                <label for="renombrarInventarioNombre">Nombre del inventario:</label>
                <input type="text" name="nombre" id="renombrarInventarioNombre" required />
            </div>
            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- --------------------------------------------------------------- -->


<!-- Modal Crear Bien -->
<div id="modalCrearBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCrearBien')">&times;</span>
        <h2>Nuevo Bien</h2>
        <form id="formCrearBien" autocomplete="off" action="/api/goods-inventory/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="inventarioId" name="inventarioId" />
            <div id="search-container">
                <input type="hidden" id="bien_id" name="bien_id"  />
                <label for="nombreBien">Nombre del bien:</label>
                <input type="text" name="nombre" id="nombreBien" class="form-control" placeholder="Buscar..." required />
                <ul class="suggestions"></ul>
            </div>

            <div style="display: none">
                <label for="cantidadBien">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidadBien" min="1" />
            </div>

            <div style="display: none">
                <label for="descripcionBien">Descripción:</label>
                <textarea name="descripcion" id="descripcionBien"></textarea>
            </div>

            <div style="display: none">
                <label for="marcaBien">Marca:</label>
                <input type="text" name="marca" id="marcaBien" />
            </div>

            <div style="display: none">
                <label for="modeloBien">Modelo:</label>
                <input type="text" name="modelo" id="modeloBien" />
            </div>

            <div style="display: none">
                <label for="serialBien">Serial:</label>
                <input type="text" name="serial" id="serialBien" />
            </div>

            <div style="display: none">
                <label for="estadoBien">Estado:</label>
                <select name="estado" id="estadoBien">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="en mantenimiento">En mantenimiento</option>
                </select>
            </div>

            <div style="display: none">
                <label for="colorBien">Color:</label>
                <input type="text" name="color" id="colorBien" />
            </div>

            <div style="display: none">
                <label for="condicionBien">Condición técnica:</label>
                <input type="text" name="condicion_tecnica" id="condicionBien" />
            </div>

            <div style="display: none">
                <label for="fechaIngresoBien">Fecha de ingreso:</label>
                <input type="date" name="fecha_ingreso" id="fechaIngresoBien" />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal para Cambiar Cantidad de Bien -->
<div id="modalCambiarCantidadBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCambiarCantidadBien')">&times;</span>
        <h2>Cambiar Cantidad</h2>
        <form id="formCambiarCantidadBien" action="/api/goods-inventory/update-quantity" method="POST" autocomplete="off">
            <input type="hidden" name="bien_id" id="cambiarCantidadBienId" />
            <div>
                <label for="cantidadBien">Nueva cantidad:</label>
                <input type="number" min="0" name="cantidad" id="cantidadBien" required />
            </div>
            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Mover Bien -->
<div id="modalMoverBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalMoverBien')">&times;</span>
        <h2>Mover Bien</h2>
        <form id="formMoverBien" action="/api/goods-inventory/move" method="POST" autocomplete="off">
            <input type="hidden" name="bien_id" id="moverBienId" />
            <div>
                <label for="inventarioDestinoId">Inventario destino:</label>
                <select name="inventario_destino_id" id="inventarioDestinoId" required>
                    <!-- Las opciones se cargarán dinámicamente con JavaScript -->
                </select>
            </div>
            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Mover Bien</button>
            </div>
        </form>
    </div>
</div>
