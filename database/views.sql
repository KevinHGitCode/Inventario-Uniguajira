-- 
-- VISTAS (TABLAS VIRTUALES) PARA OPTIMIZAR CONSULTAS
-- 

-- Vista para obtener la cantidad de cada bien en un inventario, aunque sean bien tipo serial
-- Vista para contar todos los bienes de un inventario, excluyendo los que tienen cantidad cero
CREATE VIEW vista_cantidades_bienes_inventario AS
SELECT 
    i.id AS inventario_id,
    b.id AS bien_id,
    i.nombre AS inventario,
    b.nombre AS bien,
    b.imagen AS imagen,
    b.tipo AS tipo,
    COALESCE(SUM(bc.cantidad), COUNT(be.id)) AS cantidad
FROM bienes b
JOIN bienes_inventarios bi ON b.id = bi.bien_id
JOIN inventarios i ON bi.inventario_id = i.id
LEFT JOIN bienes_cantidad bc ON bi.id = bc.bien_inventario_id
LEFT JOIN bienes_equipos be ON bi.id = be.bien_inventario_id
GROUP BY i.id, b.id, i.nombre, b.nombre
HAVING cantidad > 0;


-- 
-- Vista para obtener todos los bienes de tipo Serial de un inventario con todos los campos
CREATE VIEW vista_bienes_serial_inventario AS
SELECT 
    i.id AS inventario_id,
    b.id AS bien_id,
    i.nombre AS inventario,
    b.nombre AS bien,
    b.imagen AS imagen,
    bi.id AS bienes_inventarios_id,
    be.id AS bienes_equipos_id,
    be.descripcion,
    be.marca,
    be.modelo,
    be.serial,
    be.estado,
    be.color,
    be.condiciones_tecnicas,
    be.fecha_ingreso,
    be.fecha_salida
FROM bienes b
JOIN bienes_inventarios bi ON b.id = bi.bien_id
JOIN inventarios i ON bi.inventario_id = i.id
JOIN bienes_equipos be ON bi.id = be.bien_inventario_id;


-- Vista para obtener el número total de los bienes en el sistema
-- Incluye bienes con cantidad cero para identificar si un bien no tiene existencias en ningún inventario
CREATE VIEW vista_total_bienes_sistema AS
SELECT 
    b.id AS bien_id,
    b.nombre AS bien,
    b.tipo AS tipo_bien,
    b.imagen AS imagen, -- Agregar la columna para mostrar el campo "imagen"
    COALESCE(SUM(bc.cantidad), COUNT(be.id), 0) AS total_cantidad
FROM bienes b
LEFT JOIN bienes_inventarios bi ON b.id = bi.bien_id
LEFT JOIN bienes_cantidad bc ON bi.id = bc.bien_inventario_id
LEFT JOIN bienes_equipos be ON bi.id = be.bien_inventario_id
GROUP BY b.id, b.nombre;
