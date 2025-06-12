<!-- ====================================================== -->
<!-- ============== MODALES DE record.PHP =================== -->
<!-- ====================================================== -->

<!-- Modal de Filtros -->
<div id="Modalfiltrarhistorial" class="modal">
    <div class="modal-content modal-content-large horizontal-layout">
        <!-- Body principal en columnas -->
        <div class="modal-body filters-horizontal">

            <!-- Filtro por Usuario -->
            <div class="filter-section" style="border: 2px solid red;">
                <h3 class="section-title">
                    <i class="fa fa-user section-icon"></i>
                    Usuarios
                </h3>
                <div class="select-all">
                    <label class="checkbox-container">
                        <input type="checkbox" id="allUsers" onchange="toggleAllUsers()">
                        <span class="checkmark"></span>
                        <span class="checkbox-label">Todos los usuarios</span>
                    </label>
                </div>
                <div class="options-list" id="userList">
                    <!-- Aquí se insertarán dinámicamente los usuarios con JS -->
                </div>
            </div>

            <!-- Filtro por Acción -->
            <div class="filter-section" style="border: 2px solid red;">
                <h3 class="section-title">
                    <i class="fa fa-star section-icon"></i>
                    Acciones
                </h3>
                <div class="select-all">
                    <label class="checkbox-container">
                        <input type="checkbox" id="allActions" onchange="toggleAllActions()">
                        <span class="checkmark"></span>
                        <span class="checkbox-label">Todas las acciones</span>
                    </label>
                </div>
                <div class="options-list">
                    <label class="checkbox-container">
                        <input type="checkbox" class="action-checkbox" value="INSERT" onchange="updateActionSelection()">
                        <span class="checkmark"></span>
                        <span class="checkbox-label">INSERT</span>
                    </label>
                    <label class="checkbox-container">
                        <input type="checkbox" class="action-checkbox" value="UPDATE" onchange="updateActionSelection()">
                        <span class="checkmark"></span>
                        <span class="checkbox-label">UPDATE</span>
                    </label>
                    <label class="checkbox-container">
                        <input type="checkbox" class="action-checkbox" value="DELETE" onchange="updateActionSelection()">
                        <span class="checkmark"></span>
                        <span class="checkbox-label">DELETE</span>
                    </label>
                </div>
            </div>

            <!-- Filtro por Fecha -->
            <div class="filter-section" style="border: 2px solid red;">
                <h3 class="section-title">
                    <i class="fa fa-calendar section-icon"></i>
                    Rango de Fechas
                </h3>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <label>
                        <span>Desde</span>
                        <input type="date" id="dateFrom" class="form-input" />
                    </label>
                    <label>
                        <span>Hasta</span>
                        <input type="date" id="dateTo" class="form-input" />
                    </label>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="clearFilters()">
                <i class="fa fa-eraser"></i>
                Limpiar Campos
            </button>
            <button class="btn btn-primary" onclick="applyFilters()">
                <i class="fa fa-check"></i>
                Aplicar
            </button>
        </div>
        <span class="close" onclick="ocultarModal('#Modalfiltrarhistorial')">&times;</span>
    </div>
</div>