<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="content">
    <h2>Lista de bienes</h2>

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
        <button id="btnCrear" class="create-btn" onclick="mostrarModal('#modalCrear')">Crear</button>
        <?php endif; ?>

    </div>

    <div class="bienes-grid">
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

</div>
