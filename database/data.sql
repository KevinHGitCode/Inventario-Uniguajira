INSERT INTO usuarios (nombre, email, contraseña, rol)
VALUES ('Administrador', 'admin@email.com', 'Administrador', 'Administrador');

---------------------------------------------------------------------------------------

INSERT INTO grupos (nombre)
VALUES ('Bloque A'), ('Bloque B');

---------------------------------------------------------------------------------------

-- Obtener el ID del Bloque A y Bloque B
SET @grupo_a_id = (SELECT id FROM grupos WHERE nombre = 'Bloque A');
SET @grupo_b_id = (SELECT id FROM grupos WHERE nombre = 'Bloque B');

---------------------------------------------------------------------------------------

-- Insertar inventarios para el Bloque A
INSERT INTO inventarios (nombre, grupo_id, estado_conservacion)
VALUES 
    ('1A', @grupo_a_id, 'bueno'),
    ('2A', @grupo_a_id, 'bueno'),
    ('3A', @grupo_a_id, 'bueno');

---------------------------------------------------------------------------------------

-- Insertar inventarios para el Bloque B
INSERT INTO inventarios (nombre, grupo_id, estado_conservacion)
VALUES 
    ('1B', @grupo_b_id, 'bueno');

---------------------------------------------------------------------------------------

INSERT INTO tipos_bienes (nombre)
VALUES 
    ('pupitres'),
    ('escritorio de docente'),
    ('abanicos de pared'),
    ('abanicos de techo'),
    ('tablero'),
    ('lámparas'),
    ('sillas de docente'),
    ('puertas'),
    ('ventanas'),
    ('botes de basura'),
    ('gabinetes de red'),
    ('escritorio');

---------------------------------------------------------------------------------------

-- Obtener IDs de los inventarios
SET @inventario_1a_id = (SELECT id FROM inventarios WHERE nombre = '1A');
SET @inventario_2a_id = (SELECT id FROM inventarios WHERE nombre = '2A');
SET @inventario_3a_id = (SELECT id FROM inventarios WHERE nombre = '3A');
SET @inventario_1b_id = (SELECT id FROM inventarios WHERE nombre = '1B');

---------------------------------------------------------------------------------------

-- Obtener IDs de los tipos de bienes
SET @pupitres_id = (SELECT id FROM tipos_bienes WHERE nombre = 'pupitres');
SET @escritorio_docente_id = (SELECT id FROM tipos_bienes WHERE nombre = 'escritorio de docente');
SET @abanicos_techo_id = (SELECT id FROM tipos_bienes WHERE nombre = 'abanicos de techo');
SET @tablero_id = (SELECT id FROM tipos_bienes WHERE nombre = 'tablero');
SET @lamparas_id = (SELECT id FROM tipos_bienes WHERE nombre = 'lámparas');
SET @botes_basura_id = (SELECT id FROM tipos_bienes WHERE nombre = 'botes de basura');

---------------------------------------------------------------------------------------

-- Insertar bienes de tipo cantidad para 1A
INSERT INTO bienes (nombre, tipo_bien_id, inventario_id)
VALUES
    ('Pupitres 1A', @pupitres_id, @inventario_1a_id),
    ('Tablero 1A', @tablero_id, @inventario_1a_id),
    ('Bote de basura 1A', @botes_basura_id, @inventario_1a_id),
    ('Escritorio docente 1A', @escritorio_docente_id, @inventario_1a_id),
    ('Lámparas 1A', @lamparas_id, @inventario_1a_id),
    ('Abanicos de techo 1A', @abanicos_techo_id, @inventario_1a_id);
-- TODO: ELIMINAR EL CAMPO NOMBRE DE LA TABLA BIENES
---------------------------------------------------------------------------------------

-- Obtener IDs de los bienes insertados
SET @pupitres_1a_id = LAST_INSERT_ID() - 5;
SET @tablero_1a_id = LAST_INSERT_ID() - 4;
SET @bote_basura_1a_id = LAST_INSERT_ID() - 3;
SET @escritorio_docente_1a_id = LAST_INSERT_ID() - 2;
SET @lamparas_1a_id = LAST_INSERT_ID() - 1;
SET @abanicos_techo_1a_id = LAST_INSERT_ID();

---------------------------------------------------------------------------------------

-- Insertar cantidades para los bienes de tipo cantidad
INSERT INTO bienes_cantidad (bien_id, cantidad)
VALUES 
    (@pupitres_1a_id, 30),
    (@tablero_1a_id, 1),
    (@bote_basura_1a_id, 1),
    (@escritorio_docente_1a_id, 1),
    (@lamparas_1a_id, 4),
    (@abanicos_techo_1a_id, 3);

---------------------------------------------------------------------------------------

-- Insertar bienes de tipo cantidad para 2A
INSERT INTO bienes (nombre, tipo_bien_id, inventario_id)
VALUES 
    ('Pupitres 2A', @pupitres_id, @inventario_2a_id),
    ('Tablero 2A', @tablero_id, @inventario_2a_id),
    ('Bote de basura 2A', @botes_basura_id, @inventario_2a_id),
    ('Escritorio docente 2A', @escritorio_docente_id, @inventario_2a_id),
    ('Lámparas 2A', @lamparas_id, @inventario_2a_id),
    ('Abanicos de techo 2A', @abanicos_techo_id, @inventario_2a_id);

---------------------------------------------------------------------------------------

-- Obtener IDs de los bienes insertados
SET @pupitres_2a_id = LAST_INSERT_ID() - 5;
SET @tablero_2a_id = LAST_INSERT_ID() - 4;
SET @bote_basura_2a_id = LAST_INSERT_ID() - 3;
SET @escritorio_docente_2a_id = LAST_INSERT_ID() - 2;
SET @lamparas_2a_id = LAST_INSERT_ID() - 1;
SET @abanicos_techo_2a_id = LAST_INSERT_ID();

---------------------------------------------------------------------------------------

-- Insertar cantidades para los bienes de tipo cantidad
INSERT INTO bienes_cantidad (bien_id, cantidad)
VALUES 
    (@pupitres_2a_id, 30),
    (@tablero_2a_id, 1),
    (@bote_basura_2a_id, 1),
    (@escritorio_docente_2a_id, 1),
    (@lamparas_2a_id, 4),
    (@abanicos_techo_2a_id, 3);

---------------------------------------------------------------------------------------

-- Insertar bienes de tipo cantidad para 3A
INSERT INTO bienes (nombre, tipo_bien_id, inventario_id)
VALUES 
    ('Pupitres 3A', @pupitres_id, @inventario_3a_id),
    ('Tablero 3A', @tablero_id, @inventario_3a_id),
    ('Bote de basura 3A', @botes_basura_id, @inventario_3a_id),
    ('Escritorio docente 3A', @escritorio_docente_id, @inventario_3a_id),
    ('Lámparas 3A', @lamparas_id, @inventario_3a_id),
    ('Abanicos de techo 3A', @abanicos_techo_id, @inventario_3a_id);

---------------------------------------------------------------------------------------

-- Obtener IDs de los bienes insertados
SET @pupitres_3a_id = LAST_INSERT_ID() - 5;
SET @tablero_3a_id = LAST_INSERT_ID() - 4;
SET @bote_basura_3a_id = LAST_INSERT_ID() - 3;
SET @escritorio_docente_3a_id = LAST_INSERT_ID() - 2;
SET @lamparas_3a_id = LAST_INSERT_ID() - 1;
SET @abanicos_techo_3a_id = LAST_INSERT_ID();

---------------------------------------------------------------------------------------

-- Insertar cantidades para los bienes de tipo cantidad
INSERT INTO bienes_cantidad (bien_id, cantidad)
VALUES 
    (@pupitres_3a_id, 30),
    (@tablero_3a_id, 1),
    (@bote_basura_3a_id, 1),
    (@escritorio_docente_3a_id, 1),
    (@lamparas_3a_id, 4),
    (@abanicos_techo_3a_id, 3);

---------------------------------------------------------------------------------------

-- Insertar bienes de tipo cantidad para 1B
INSERT INTO bienes (nombre, tipo_bien_id, inventario_id)
VALUES 
    ('Pupitres 1B', @pupitres_id, @inventario_1b_id),
    ('Tablero 1B', @tablero_id, @inventario_1b_id),
    ('Bote de basura 1B', @botes_basura_id, @inventario_1b_id),
    ('Escritorio docente 1B', @escritorio_docente_id, @inventario_1b_id),
    ('Lámparas 1B', @lamparas_id, @inventario_1b_id),
    ('Abanicos de techo 1B', @abanicos_techo_id, @inventario_1b_id);

---------------------------------------------------------------------------------------

-- Obtener IDs de los bienes insertados
SET @pupitres_1b_id = LAST_INSERT_ID() - 5;
SET @tablero_1b_id = LAST_INSERT_ID() - 4;
SET @bote_basura_1b_id = LAST_INSERT_ID() - 3;
SET @escritorio_docente_1b_id = LAST_INSERT_ID() - 2;
SET @lamparas_1b_id = LAST_INSERT_ID() - 1;
SET @abanicos_techo_1b_id = LAST_INSERT_ID();

---------------------------------------------------------------------------------------

-- Insertar cantidades para los bienes de tipo cantidad
INSERT INTO bienes_cantidad (bien_id, cantidad)
VALUES 
    (@pupitres_1b_id, 30),
    (@tablero_1b_id, 1),
    (@bote_basura_1b_id, 1),
    (@escritorio_docente_1b_id, 1),
    (@lamparas_1b_id, 4),
    (@abanicos_techo_1b_id, 3);