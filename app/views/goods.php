<?php require_once 'app/controllers/sessionCheck.php'; ?>

<body>
    <div class="content">
        <h2>Lista de bienes</h2>

        <div class="top-bar">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Buscar o agregar bien" class="search-bar">
                <i class="search-icon fas fa-search"></i>
            </div>
            <button class="create-btn">Crear</button>
        </div>

        <div class="bienes-grid">
            <?php foreach ($dataGoods as $bien): ?>
                <div class="bien-card">
                <img src="<?= htmlspecialchars($bien['imagen']) ?>" class="bien-image">
                    <div class="bien-info">
                        <h3><?= htmlspecialchars($bien['bien']) ?></h3>
                        <p>Cantidad: <?= $bien['total_cantidad'] ?></p>
                    </div>
                    <div class="actions">
                        <a href="#"><i class="fas fa-edit"></i></a>
                        <a href="#"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
