-- DESCRIPCION DE LOS DATOS DE PRUEBA 
-- 1. Insertar el usuario administrador
-- 2. Insertar los grupos del bloque A y bloque B
-- 3. Insertar los inventarios para el grupo A (1A, 2A, 3A) y el grupo B (1B)
-- 4. Insertar los tipos de bienes
-- 5. Insertar los bienes en cada salón (pupitres, tableros, abanicos, etc.)
--

-- 1. Insertar usuarios
-- // TODO: cambiar las contraseñas por contraseñas encriptadas
INSERT INTO usuarios (nombre, nombre_usuario, email, contraseña, rol)
VALUES 
    ('Administrador', 'admin', 'admin@email.com', 'admin', 'administrador'),
    ('Daniel', 'daniel', 'daniel@email.com', '1234', 'administrador'),
    ('Luis', 'luis', 'luis@email.com', '1234', 'administrador'),
    ('Renzo', 'renzo', 'renzo@email.com', '1234', 'administrador'),
    ('Kevin', 'kevin', 'kevin@email.com', '1234', 'administrador'),
    ('Consultor', 'consultor', 'consultor@email.com', 'consul', 'consultor');


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
INSERT INTO grupos (nombre)
VALUES ('Bloque A'), ('Bloque B'), ('Salas');

-- 3. Insertar los inventarios para el grupo A (1A, 2A, 3A) y el grupo B (1B)
INSERT INTO inventarios (nombre, grupo_id, estado_conservacion)
VALUES 
    ('1A', 1, 'bueno'),  -- 1A pertenece al grupo Bloque A (id=1)
    ('2A', 1, 'bueno'),  -- 2A pertenece al grupo Bloque A (id=1)
    ('3A', 1, 'bueno'),  -- 3A pertenece al grupo Bloque A (id=1)
    ('1B', 2, 'bueno'),  -- 1B pertenece al grupo Bloque B (id=2)
    ('Sala de redes', 3, 'bueno');  -- Sala de redes pertenece al grupo Salas (id=3)
    

-- 4. Insertar los tipos de bienes
INSERT INTO bienes (id, nombre, tipo)
VALUES 
    (1, 'pupitres', 'Cantidad'),
    (2, 'escritorio de docente', 'Cantidad'),
    (3, 'abanicos de pared', 'Cantidad'),
    (4, 'abanicos de techo', 'Cantidad'),
    (5, 'tablero', 'Cantidad'),
    (6, 'lámparas', 'Cantidad'),
    (7, 'sillas de docente', 'Cantidad'),
    (8, 'puertas', 'Cantidad'),
    (9, 'ventanas', 'Cantidad'),
    (10, 'botes de basura', 'Cantidad'),
    (11, 'gabinetes de red', 'Cantidad'),
    (12, 'escritorio', 'Cantidad');

-- 5. Insertar los bienes en cada salón
-- Primero, insertar la relación entre bienes e inventarios en la tabla bienes_inventarios
-- Luego, insertar las cantidades en la tabla bienes_cantidad

-- Salón 1A
INSERT INTO bienes_inventarios (bien_id, inventario_id)
VALUES 
    (1, 1),  -- pupitres en 1A
    (5, 1),  -- tablero en 1A
    (10, 1), -- bote de basura en 1A
    (2, 1),  -- escritorio de docente en 1A
    (6, 1),  -- lámparas en 1A
    (4, 1);  -- abanicos de techo en 1A

INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
VALUES 
    (1, 30),  -- 30 pupitres en 1A
    (2, 1),   -- 1 tablero en 1A
    (3, 1),   -- 1 bote de basura en 1A
    (4, 1),   -- 1 escritorio de docente en 1A
    (5, 4),   -- 4 lámparas en 1A
    (6, 3);   -- 3 abanicos de techo en 1A

-- Salón 2A
INSERT INTO bienes_inventarios (bien_id, inventario_id)
VALUES 
    (1, 2),  -- pupitres en 2A
    (5, 2),  -- tablero en 2A
    (10, 2), -- bote de basura en 2A
    (2, 2),  -- escritorio de docente en 2A
    (6, 2),  -- lámparas en 2A
    (4, 2);  -- abanicos de techo en 2A

INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
VALUES 
    (7, 30),  -- 30 pupitres en 2A
    (8, 1),   -- 1 tablero en 2A
    (9, 1),   -- 1 bote de basura en 2A
    (10, 1),  -- 1 escritorio de docente en 2A
    (11, 4),  -- 4 lámparas en 2A
    (12, 3);  -- 3 abanicos de techo en 2A

-- Salón 3A
INSERT INTO bienes_inventarios (bien_id, inventario_id)
VALUES 
    (1, 3),  -- pupitres en 3A
    (5, 3),  -- tablero en 3A
    (10, 3), -- bote de basura en 3A
    (2, 3),  -- escritorio de docente en 3A
    (6, 3),  -- lámparas en 3A
    (4, 3);  -- abanicos de techo en 3A

INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
VALUES 
    (13, 30), -- 30 pupitres en 3A
    (14, 1),  -- 1 tablero en 3A
    (15, 1),  -- 1 bote de basura en 3A
    (16, 1),  -- 1 escritorio de docente en 3A
    (17, 4),  -- 4 lámparas en 3A
    (18, 3);  -- 3 abanicos de techo en 3A

-- Salón 1B
INSERT INTO bienes_inventarios (bien_id, inventario_id)
VALUES 
    (1, 4),  -- pupitres en 1B
    (5, 4),  -- tablero en 1B
    (10, 4), -- bote de basura en 1B
    (2, 4),  -- escritorio de docente en 1B
    (6, 4),  -- lámparas en 1B
    (4, 4);  -- abanicos de techo en 1B

INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
VALUES 
    (19, 30), -- 30 pupitres en 1B
    (20, 1),  -- 1 tablero en 1B
    (21, 1),  -- 1 bote de basura en 1B
    (22, 1),  -- 1 escritorio de docente en 1B
    (23, 4),  -- 4 lámparas en 1B
    (24, 3);  -- 3 abanicos de techo en 1B


-- Insertar más bienes en la tabla bienes
INSERT INTO bienes (id, nombre, tipo)
VALUES 
    (13, 'Computadores', 'Serial'),
    (14, 'Sillas', 'Cantidad'),
    (15, 'Rack grande', 'Cantidad'),
    (16, 'Rack mediano', 'Cantidad');

-- Relacionar bienes con el inventario "Sala de redes"
INSERT INTO bienes_inventarios (bien_id, inventario_id)
VALUES 
    (13, 5),  -- Computadores
    (14, 5),  -- Sillas
    (15, 5),  -- Rack grande
    (16, 5);  -- Rack mediano

-- Insertar cantidades para los bienes de tipo Cantidad
INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
VALUES 
    (26, 25),  -- 25 sillas
    (27, 1),   -- 1 rack grande
    (28, 1);   -- 1 rack mediano

-- Insertar detalles de los computadores en la tabla bienes_equipos
INSERT INTO bienes_equipos (bien_inventario_id, descripcion, marca, modelo, serial, estado, color, condiciones_tecnicas, fecha_ingreso)
VALUES 
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN001', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN002', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN003', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN004', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN005', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN006', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN007', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN008', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN009', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN010', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN011', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN012', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN013', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN014', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN015', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN016', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN017', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN018', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN019', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN020', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN021', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN022', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN023', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN024', 'activo', 'Negro', 'Buen estado', '2025-03-31'),
    (25, 'Computador de escritorio', 'HP', 'ProDesk 400 G6', 'SN025', 'activo', 'Negro', 'Buen estado', '2025-03-31');