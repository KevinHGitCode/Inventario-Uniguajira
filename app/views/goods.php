<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="content">    
    <div class="goods-header">
        <h2>Lista de bienes</h2>
        <!-- Botón para subir Excel -->
        <label class="excel-upload-btn" title="Subir Excel" onclick="toggleExcelUploadUI()">
            <i class="fas fa-file-excel"></i>
        </label>
    </div>

    <div class="top-bar">
        <div class="search-container">
            <input
                type="text"
                id="searchGood"
                placeholder="Buscar o agregar bien"
                class="search-bar searchInput"
            />
            <i class="search-icon fas fa-search"></i>
        </div>

        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
        <button id="btnCrear" class="create-btn" onclick="mostrarModal('#modalCrearBien')">Crear</button>
        <?php endif; ?>

    </div>

    <?php if (empty($dataGoods)): ?>
    <div class="empty-state">
        <i class="fas fa-box-open fa-3x"></i>
        <p>No hay bienes disponibles</p>
    </div>
    <?php else: ?>
    <div id="bienes-grid" class="bienes-grid">
        <?php foreach ($dataGoods as $bien): ?>
        <div class="bien-card card-item">
            <img
                src="<?= htmlspecialchars($bien['imagen'] ?: 'assets/uploads/img/goods/default.jpg') ?>"
                class="bien-image"
            />
            <div class="bien-info">
                <h3 class="name-item"><?= htmlspecialchars($bien['bien']) ?> <img
                    src="assets/icons/<?= $bien['tipo_bien'] === "Cantidad" ? 'bienCantidad.svg' : 'bienSerial.svg' ?>"
                    alt="Icono de tipo de bien"
                    class="bien-icon"
                /></h3>
                <p>
                    Cantidad:
                    <?= $bien['total_cantidad'] ?>
                </p>
            </div>

            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <div class="actions">
                <a
                    class="btn-editar"
                    onclick="ActualizarBien(<?= $bien['bien_id'] ?>, '<?= htmlspecialchars($bien['bien']) ?>')"
                    ><i class="fas fa-edit"></i
                ></a>
                <a
                    class="btn-eliminar"
                    onclick="eliminarBien(<?= $bien['bien_id'] ?>)"
                    ><i class="fas fa-trash"></i
                ></a>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div id="excel-upload-content" class="hidden">
        <h3>Cargar datos de bienes desde Excel</h3>
        <!-- Espacio para subir el archivo (un cuadro con bordes punteados) 
                que dice "Arrastra y suelta un archivo aquí"
        -->
        <div id="excel-upload-area" class="excel-upload-area" style="border: 2px dashed #ccc; padding: 20px; text-align: center;">
            <p>Arrastra y suelta un archivo aquí o haz clic para seleccionar un archivo</p>
            <input
                type="file"
                id="excelFileInput"
                accept=".xlsx, .xls, .csv"
                class="hidden"
                onchange="handleFileUpload(event)"
            />
            <button id="btn-select-excel" class="select-btn" onclick="document.getElementById('excelFileInput').click()">
                Seleccionar archivo
            </button>
        </div>

<br>

        <h3>Previsualización de datos</h3>
        <div id="excel-preview-table">
            <!-- Tabla de previsualización -->
            <table class="hidden">
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>Tipo</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody id="excel-preview-body">
                    <!-- Las filas se agregarán dinámicamente aquí -->
                </tbody>
            </table>
        </div>

        <button onclick="btnClearExcelUploadUI()">Cancelar</button>
        <button id="btnEnviarExcel" onclick="sendGoodsData(collectGoodsData())" disabled>Enviar</button>

    </div>

</div>

