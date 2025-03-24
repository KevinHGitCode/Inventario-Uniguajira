-- DESCRIPCION DE LOS DATOS DE PRUEBA 
-- 1. Insertar el usuario administrador
-- 2. Insertar los grupos del bloque A y bloque B
-- 3. Insertar los inventarios para el grupo A (1A, 2A, 3A) y el grupo B (1B)
-- 4. Insertar los tipos de bienes
-- 5. Insertar los bienes en cada salón (pupitres, tableros, abanicos, etc.)
--

-- 1. Insertar el usuario administrador
INSERT INTO usuarios (nombre, email, contraseña, rol)
VALUES ('Administrador', 'admin@email.com', 'Administrador', 'administrador');

-- 2. Insertar los grupos del bloque A y bloque B
INSERT INTO grupos (nombre)
VALUES ('Bloque A'), ('Bloque B');

-- 3. Insertar los inventarios para el grupo A (1A, 2A, 3A) y el grupo B (1B)
INSERT INTO inventarios (nombre, grupo_id, estado_conservacion)
VALUES 
    ('1A', 1, 'bueno'),  -- 1A pertenece al grupo Bloque A (id=1)
    ('2A', 1, 'bueno'),  -- 2A pertenece al grupo Bloque A (id=1)
    ('3A', 1, 'bueno'),  -- 3A pertenece al grupo Bloque A (id=1)
    ('1B', 2, 'bueno');  -- 1B pertenece al grupo Bloque B (id=2)

-- 4. Insertar los tipos de bienes
INSERT INTO bienes (nombre, tipo)
VALUES 
    ('pupitres', 'Cantidad'),
    ('escritorio de docente', 'Cantidad'),
    ('abanicos de pared', 'Cantidad'),
    ('abanicos de techo', 'Cantidad'),
    ('tablero', 'Cantidad'),
    ('lámparas', 'Cantidad'),
    ('sillas de docente', 'Cantidad'),
    ('puertas', 'Cantidad'),
    ('ventanas', 'Cantidad'),
    ('botes de basura', 'Cantidad'),
    ('gabinetes de red', 'Cantidad'),
    ('escritorio', 'Cantidad');

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