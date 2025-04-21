-- DESCRIPCION DE LOS DATOS DE PRUEBA 
-- 1. Insertar usuarios
-- 2. Insertar los grupos
-- 3. Insertar los inventarios en los grupos
-- 4. Insertar los tipos de bienes
-- 5. Insertar los bienes en cada salón inventario

-- 1. Insertar usuarios
INSERT INTO usuarios (nombre, nombre_usuario, email, contraseña, rol)
VALUES 
    ('Administrador', 'admin', 'admin@email.com', '$2y$10$DY06BnTlLyr8z0b/IDjTXuN2pRNL9rCt0zYn0ZBDAsgjZsBmF3Hqq', 'administrador'), -- Contraseña: admin
    ('Daniel', 'Danie1l6', 'daniel@email.com', '$2y$10$Z6Q86q9MRsyZdSrcpSjFA.uvfq1mS1U2DkaVwXp1EtHtJhNyXkDRC', 'administrador'), -- Contraseña: 1234
    ('Luis', 'luis', 'luis@email.com', '$2y$10$WuTyyr1liA1oXkq1baarA./Yjf6n9bO3o68LzKtYY9XyUlZXTwOFm', 'administrador'), -- Contraseña: 1234
    ('Renzo', 'renzo', 'renzo@email.com', '$2y$10$4xh8/0LXMaebWN69C.0QwOPfKkJ.QTHQFgrhnZO0O9wKon495/xhG', 'administrador'), -- Contraseña: 1234
    ('Kevin', 'kevin', 'kevin@email.com', '$2y$10$NwbZSqVtN5E6vMTu8nCxrejlRYYZRVxKHs0QK/3zfxmKlkgOXv/sm', 'administrador'), -- Contraseña: 1234
    ('Consultor', 'consultor', 'consultor@email.com', '$2y$10$Ki86sKsnOrXFUI.RoGyAsum9hcbzR5KJQZ269hqEjThJ0vrHIS3ou', 'consultor'); -- Contraseña: consul


-- ---------------------------------------------------------------------------------------------------------
-- Insertar tareas
-- Insertar tareas para el usuario con id = 1
INSERT INTO tareas (nombre, descripcion, fecha, estado, usuario_id)
VALUES
    ('Revisar inventario general', 'Revisar el estado de todos los inventarios y generar un reporte.', '2025-04-01', 'por hacer', 1),
    ('Actualizar bienes', 'Actualizar la información de bienes en el sistema.', '2025-04-02', 'por hacer', 1),
    ('Generar reporte mensual', 'Generar y exportar el reporte mensual de inventarios.', '2025-04-03', 'por hacer', 1),
    ('Auditar inventario Bloque A', 'Realizar una auditoría completa del Bloque A.', '2025-04-04', 'por hacer', 1),
    ('Capacitar usuarios', 'Capacitar a los usuarios sobre el uso del sistema de inventarios.', '2025-04-05', 'por hacer', 1);

-- Insertar 2 tareas para los demás usuarios
INSERT INTO tareas (nombre, descripcion, fecha, estado, usuario_id)
VALUES
    ('Revisar inventario Bloque B', 'Revisar el estado del inventario del Bloque B.', '2025-04-01', 'por hacer', 2),
    ('Actualizar bienes Bloque B', 'Actualizar la información de bienes en el Bloque B.', '2025-04-02', 'por hacer', 2),
    ('Revisar inventario Bloque C', 'Revisar el estado del inventario del Bloque C.', '2025-04-01', 'por hacer', 3),
    ('Actualizar bienes Bloque C', 'Actualizar la información de bienes en el Bloque C.', '2025-04-02', 'por hacer', 3),
    ('Revisar inventario Bloque D', 'Revisar el estado del inventario del Bloque D.', '2025-04-01', 'por hacer', 4),
    ('Actualizar bienes Bloque D', 'Actualizar la información de bienes en el Bloque D.', '2025-04-02', 'por hacer', 4),
    ('Revisar inventario Bloque E', 'Revisar el estado del inventario del Bloque E.', '2025-04-01', 'por hacer', 5),
    ('Actualizar bienes Bloque E', 'Actualizar la información de bienes en el Bloque E.', '2025-04-02', 'por hacer', 5);

-- ---------------------------------------------------------------------------------------------------------

-- 2. Insertar los grupos del bloque A y bloque B
INSERT INTO grupos (id, nombre)
VALUES 
    (1, 'Bloque A'), 
    (2, 'Bloque B'), 
    (3, 'Bloque C'), 
    (4, 'Bloque D'), 
    (5, 'Salas'), 
    (6, 'Bloque Administrativo');

-- 3. Insertar los inventarios para los grupos
INSERT INTO inventarios (id, nombre, grupo_id, estado_conservacion)
VALUES 
    -- BLOQUES
    (1, '1A', 1, 'bueno'),  -- 1A pertenece al grupo Bloque A (id=1)
    (2, '2A', 1, 'bueno'),  -- 2A pertenece al grupo Bloque A (id=1)
    (3, '3A', 1, 'bueno'),  -- 3A pertenece al grupo Bloque A (id=1)
    (4, '4A', 1, 'bueno'),  -- 4A pertenece al grupo Bloque A (id=1)
    (5, '5A', 1, 'bueno'),  -- 5A pertenece al grupo Bloque A (id=1)
    (6, '6A', 1, 'bueno'),  -- 6A pertenece al grupo Bloque A (id=1)
    (7, '7A', 1, 'bueno'),  -- 7A pertenece al grupo Bloque A (id=1)
    (8, '8A', 1, 'bueno'),  -- 8A pertenece al grupo Bloque A (id=1)
    (9, '9A', 1, 'bueno'),  -- 9A pertenece al grupo Bloque A (id=1)
    (10, '10A', 1, 'bueno'),  -- 10A pertenece al grupo Bloque A (id=1)
    (11, '1B', 2, 'bueno'),  -- 1B pertenece al grupo Bloque B (id=2)
    (12, '2B', 2, 'bueno'),  -- 2B pertenece al grupo Bloque B (id=2)
    (13, '3B', 2, 'bueno'),  -- 3B pertenece al grupo Bloque B (id=2)
    (14, '4B', 2, 'bueno'),  -- 4B pertenece al grupo Bloque B (id=2)
    (15, '5B', 2, 'bueno'),  -- 5B pertenece al grupo Bloque B (id=2)
    (16, '6B', 2, 'bueno'),  -- 6B pertenece al grupo Bloque B (id=2)
    (17, '7B', 2, 'bueno'),  -- 7B pertenece al grupo Bloque B (id=2)
    (18, '8B', 2, 'bueno'),  -- 8B pertenece al grupo Bloque B (id=2)
    (19, '9B', 2, 'bueno'),  -- 9B pertenece al grupo Bloque B (id=2)
    (20, '10B', 2, 'bueno'),  -- 10B pertenece al grupo Bloque B (id=2)
    (21, '1C', 3, 'bueno'),  -- 1C pertenece al grupo Bloque C (id=3)
    (22, '2C', 3, 'bueno'),  -- 2C pertenece al grupo Bloque C (id=3)
    (23, '3C', 3, 'bueno'),  -- 3C pertenece al grupo Bloque C (id=3)
    (24, '4C', 3, 'bueno'),  -- 4C pertenece al grupo Bloque C (id=3)
    (25, '5C', 3, 'bueno'),  -- 5C pertenece al grupo Bloque C (id=3)
    (26, '6C', 3, 'bueno'),  -- 6C pertenece al grupo Bloque C (id=3)
    (27, '7C', 3, 'bueno'),  -- 7C pertenece al grupo Bloque C (id=3)
    (28, '8C', 3, 'bueno'),  -- 8C pertenece al grupo Bloque C (id=3)
    (29, '9C', 3, 'bueno'),  -- 9C pertenece al grupo Bloque C (id=3)
    (30, '10C', 3, 'bueno'),  -- 10C pertenece al grupo Bloque C (id=3)
    (31, '1D', 4, 'bueno'),  -- 1D pertenece al grupo Bloque D (id=4)
    (32, '2D', 4, 'bueno'),  -- 2D pertenece al grupo Bloque D (id=4)
    (33, '3D', 4, 'bueno'),  -- 3D pertenece al grupo Bloque D (id=4)
    (34, '4D', 4, 'bueno'),  -- 4D pertenece al grupo Bloque D (id=4)
    (35, '5D', 4, 'bueno'),  -- 5D pertenece al grupo Bloque D (id=4)
    (36, '6D', 4, 'bueno'),  -- 6D pertenece al grupo Bloque D (id=4)

    -- SALAS
    (37, 'Sala de redes', 5, 'bueno'),  -- Sala de redes pertenece al grupo Salas (id=5)
    (38, 'Audiovisuales 1', 5, 'bueno'),  -- Audiovisuales 1 pertenece al grupo Salas (id=5)
    (39, 'Audiovisuales 2', 5, 'bueno');  -- Audiovisuales 2 pertenece al grupo Salas (id=5)
    

-- 4. Insertar los tipos de bienes
INSERT INTO bienes (id, nombre, tipo, imagen)
VALUES 
    (1, 'Pupitres', 'Cantidad'. 'assets/uploads/img/goods/img_6805e94287b04.png'),
    (2, 'Tableros', 'Cantidad', 'assets/uploads/img/goods/img_67fb3abe51fcf.png'),
    (3, 'Abanicos de techo', 'Cantidad', 'assets/uploads/img/goods/img_67fb35f2cc3e3.png'),
    (4, 'Escritorio de docente', 'Cantidad', 'assets/uploads/img/goods/img_67fb350ce3c8d.png'),
    (5, 'Lámparas', 'Cantidad', 'assets/uploads/img/goods/img_67fb39b659404.webp'),
    (6, 'Puertas', 'Cantidad', 'assets/uploads/img/goods/img_67fb3a06c0295.jpg'),
    (7, 'Botes de basura', 'Cantidad', 'assets/uploads/img/goods/img_67fb37ec5e0cd.png');

-- 5. Insertar los bienes en cada salón
-- Primero, insertar la relación entre bienes e inventarios en la tabla bienes_inventarios
-- Luego, insertar las cantidades en la tabla bienes_cantidad

INSERT INTO bienes_inventarios (id, bien_id, inventario_id)
VALUES 
    (1, 1, 1),  -- pupitres en 1A
    (2, 2, 1),  -- tablero en 1A
    (3, 1, 2),  -- pupitres en 2A
    (4, 2, 2),  -- tablero en 2A
    (5, 1, 3),  -- pupitres en 3A
    (6, 2, 3),  -- tablero en 3A
    (7, 1, 4),  -- pupitres en 4A
    (8, 2, 4),  -- tablero en 4A
    (9, 1, 5),  -- pupitres en 5A
    (10, 2, 5), -- tablero en 5A
    (11, 1, 6), -- pupitres en 6A
    (12, 2, 6), -- tablero en 6A
    (13, 1, 7), -- pupitres en 7A
    (14, 2, 7), -- tablero en 7A
    (15, 1, 8), -- pupitres en 8A
    (16, 2, 8), -- tablero en 8A
    (17, 1, 9), -- pupitres en 9A
    (18, 2, 9), -- tablero en 9A
    (19, 1, 10), -- pupitres en 10A
    (20, 2, 10), -- tablero en 10A
    (21, 1, 11), -- pupitres en 1B
    (22, 2, 11), -- tablero en 1B
    (23, 1, 12), -- pupitres en 2B
    (24, 2, 12), -- tablero en 2B
    (25, 1, 13), -- pupitres en 3B
    (26, 2, 13), -- tablero en 3B
    (27, 1, 14), -- pupitres en 4B
    (28, 2, 14), -- tablero en 4B
    (29, 1, 15), -- pupitres en 5B
    (30, 2, 15), -- tablero en 5B
    (31, 1, 16), -- pupitres en 6B
    (32, 2, 16), -- tablero en 6B
    (33, 1, 17), -- pupitres en 7B
    (34, 2, 17), -- tablero en 7B
    (35, 1, 18), -- pupitres en 8B
    (36, 2, 18), -- tablero en 8B
    (37, 1, 19), -- pupitres en 9B
    (38, 2, 19), -- tablero en 9B
    (39, 1, 20), -- pupitres en 10B
    (40, 2, 20), -- tablero en 10B
    (41, 1, 21), -- pupitres en 1C
    (42, 2, 21), -- tablero en 1C
    (43, 1, 22), -- pupitres en 2C
    (44, 2, 22), -- tablero en 2C
    (45, 1, 23), -- pupitres en 3C
    (46, 2, 23), -- tablero en 3C
    (47, 1, 24), -- pupitres en 4C
    (48, 2, 24), -- tablero en 4C
    (49, 1, 25), -- pupitres en 5C
    (50, 2, 25), -- tablero en 5C
    (51, 1, 26), -- pupitres en 6C
    (52, 2, 26), -- tablero en 6C
    (53, 1, 27), -- pupitres en 7C
    (54, 2, 27), -- tablero en 7C
    (55, 1, 28), -- pupitres en 8C
    (56, 2, 28), -- tablero en 8C
    (57, 1, 29), -- pupitres en 9C
    (58, 2, 29), -- tablero en 9C
    (59, 1, 30), -- pupitres en 10C
    (60, 2, 30), -- tablero en 10C
    (61, 1, 31), -- pupitres en 1D
    (62, 2, 31), -- tablero en 1D
    (63, 1, 32), -- pupitres en 2D
    (64, 2, 32), -- tablero en 2D
    (65, 1, 33), -- pupitres en 3D
    (66, 2, 33), -- tablero en 3D
    (67, 1, 34), -- pupitres en 4D
    (68, 2, 34), -- tablero en 4D
    (69, 1, 35), -- pupitres en 5D
    (70, 2, 35), -- tablero en 5D
    (71, 1, 36), -- pupitres en 6D
    (72, 2, 36); -- tablero en 6D

INSERT INTO bienes_cantidad (id, bien_inventario_id, cantidad)
VALUES 
    (1, 1, 35),  -- 35 pupitres en 1A
    (2, 2, 1),   -- 1 tablero en 1A
    (3, 3, 32),  -- 32 pupitres en 2A
    (4, 4, 2),   -- 2 tableros en 2A
    (5, 5, 30),  -- 30 pupitres en 3A
    (6, 6, 1),   -- 1 tablero en 3A
    (7, 7, 38),  -- 38 pupitres en 4A
    (8, 8, 2),   -- 2 tableros en 4A
    (9, 9, 36),  -- 36 pupitres en 5A
    (10, 10, 1),  -- 1 tablero en 5A
    (11, 11, 33), -- 33 pupitres en 6A
    (12, 12, 2),  -- 2 tableros en 6A
    (13, 13, 37), -- 37 pupitres en 7A
    (14, 14, 1),  -- 1 tablero en 7A
    (15, 15, 34), -- 34 pupitres en 8A
    (16, 16, 2),  -- 2 tableros en 8A
    (17, 17, 31), -- 31 pupitres en 9A
    (18, 18, 1),  -- 1 tablero en 9A
    (19, 19, 39), -- 39 pupitres en 10A
    (20, 20, 2),  -- 2 tableros en 10A
    (21, 21, 30), -- 30 pupitres en 1B
    (22, 22, 1),  -- 1 tablero en 1B
    (23, 23, 35), -- 35 pupitres en 2B
    (24, 24, 2),  -- 2 tableros en 2B
    (25, 25, 32), -- 32 pupitres en 3B
    (26, 26, 1),  -- 1 tablero en 3B
    (27, 27, 38), -- 38 pupitres en 4B
    (28, 28, 2),  -- 2 tableros en 4B
    (29, 29, 36), -- 36 pupitres en 5B
    (30, 30, 1),  -- 1 tablero en 5B
    (31, 31, 33), -- 33 pupitres en 6B
    (32, 32, 2),  -- 2 tableros en 6B
    (33, 33, 37), -- 37 pupitres en 7B
    (34, 34, 1),  -- 1 tablero en 7B
    (35, 35, 34), -- 34 pupitres en 8B
    (36, 36, 2),  -- 2 tableros en 8B
    (37, 37, 31), -- 31 pupitres en 9B
    (38, 38, 1),  -- 1 tablero en 9B
    (39, 39, 39), -- 39 pupitres en 10B
    (40, 40, 2),  -- 2 tableros en 10B
    (41, 41, 30), -- 30 pupitres en 1C
    (42, 42, 1),  -- 1 tablero en 1C
    (43, 43, 35), -- 35 pupitres en 2C
    (44, 44, 2),  -- 2 tableros en 2C
    (45, 45, 32), -- 32 pupitres en 3C
    (46, 46, 1),  -- 1 tablero en 3C
    (47, 47, 38), -- 38 pupitres en 4C
    (48, 48, 2),  -- 2 tableros en 4C
    (49, 49, 36), -- 36 pupitres en 5C
    (50, 50, 1),  -- 1 tablero en 5C
    (51, 51, 33), -- 33 pupitres en 6C
    (52, 52, 2),  -- 2 tableros en 6C
    (53, 53, 37), -- 37 pupitres en 7C
    (54, 54, 1),  -- 1 tablero en 7C
    (55, 55, 34), -- 34 pupitres en 8C
    (56, 56, 2),  -- 2 tableros en 8C
    (57, 57, 31), -- 31 pupitres en 9C
    (58, 58, 1),  -- 1 tablero en 9C
    (59, 59, 39), -- 39 pupitres en 10C
    (60, 60, 2),  -- 2 tableros en 10C
    (61, 61, 30), -- 30 pupitres en 1D
    (62, 62, 1),  -- 1 tablero en 1D
    (63, 63, 35), -- 35 pupitres en 2D
    (64, 64, 2),  -- 2 tableros en 2D
    (65, 65, 32), -- 32 pupitres en 3D
    (66, 66, 1),  -- 1 tablero en 3D
    (67, 67, 38), -- 38 pupitres en 4D
    (68, 68, 2),  -- 2 tableros en 4D
    (69, 69, 36), -- 36 pupitres en 5D
    (70, 70, 1),  -- 1 tablero en 5D
    (71, 71, 33), -- 33 pupitres en 6D
    (72, 72, 2);  -- 2 tableros en 6D


-- Insertar más bienes en la tabla bienes
INSERT INTO bienes (id, nombre, tipo)
VALUES 
    (8, 'Computadores', 'Serial'),
    (9, 'Sillas', 'Cantidad'),
    (10, 'Rack grande', 'Cantidad'),
    (11, 'Rack mediano', 'Cantidad');

-- Relacionar bienes con el inventario "Sala de redes"
INSERT INTO bienes_inventarios (id, bien_id, inventario_id)
VALUES 
    (73, 8, 37),  -- Computadores
    (74, 9, 37),  -- Sillas
    (75, 10, 37),  -- Rack grande
    (76, 11, 37);  -- Rack mediano

-- Insertar cantidades para los bienes de tipo Cantidad
INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
VALUES 
    (74, 25),  -- 25 sillas
    (75, 1),   -- 1 rack grande
    (76, 1);   -- 1 rack mediano

-- Insertar detalles de los computadores en la tabla bienes_equipos
INSERT INTO bienes_equipos (bien_inventario_id, descripcion, marca, modelo, serial, estado, color, condiciones_tecnicas, fecha_ingreso)
VALUES 
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN001', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN002', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN003', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN004', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN006', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN007', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN008', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN009', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN010', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN011', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN012', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN013', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN014', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN015', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN016', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN017', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN018', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN019', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN020', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN021', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN022', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN023', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN024', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (73, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN025', 'activo', 'Negro', 'Buen estado', '2025-03-31');