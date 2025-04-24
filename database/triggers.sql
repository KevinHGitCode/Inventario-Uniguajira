-- 
-- TRIGGERS MEJORADOS (DISPARADORES) PARA LLEVAR EL HISTORIAL
-- 

-- Nota: Todos los triggers asumen que la variable @usuario_actual está configurada
-- antes de realizar operaciones en la base de datos
-- Ejemplo en PHP:
-- $userId = $_SESSION['user_id']; 
-- $conn->query("SET @usuario_actual = $userId");

DELIMITER //

-- ========================
-- TRIGGERS PARA USUARIOS
-- ========================
CREATE TRIGGER after_insert_usuario
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'usuarios', NEW.id, CONCAT('Se ha creado el usuario ', NEW.nombre_usuario));
END//

CREATE TRIGGER after_update_usuario
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'UPDATE', 'usuarios', NEW.id, CONCAT('Se ha editado el usuario ', NEW.nombre_usuario));
END//

CREATE TRIGGER after_delete_usuario
AFTER DELETE ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'usuarios', OLD.id, CONCAT('Se ha eliminado el usuario ', OLD.nombre_usuario));
END//

-- ========================
-- TRIGGERS PARA TAREAS
-- ========================
CREATE TRIGGER after_insert_tarea
AFTER INSERT ON tareas
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'tareas', NEW.id, CONCAT('Se ha creado la tarea ', NEW.nombre));
END//

CREATE TRIGGER after_update_tarea
AFTER UPDATE ON tareas
FOR EACH ROW
BEGIN
    IF OLD.estado != NEW.estado AND NEW.estado = 'completado' THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'tareas', NEW.id, CONCAT('Se ha marcado como completada la tarea ', NEW.nombre));
    ELSEIF OLD.estado != NEW.estado AND NEW.estado = 'por hacer' THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'tareas', NEW.id, CONCAT('Se ha desmarcado como completada la tarea ', NEW.nombre));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'tareas', NEW.id, CONCAT('Se ha editado la tarea ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_tarea
AFTER DELETE ON tareas
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'tareas', OLD.id, CONCAT('Se ha eliminado la tarea ', OLD.nombre));
END//

-- ========================
-- TRIGGERS PARA GRUPOS
-- ========================
CREATE TRIGGER after_insert_grupo
AFTER INSERT ON grupos
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'grupos', NEW.id, CONCAT('Se ha creado el grupo ', NEW.nombre));
END//

CREATE TRIGGER after_update_grupo
AFTER UPDATE ON grupos
FOR EACH ROW
BEGIN
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'grupos', NEW.id, CONCAT('Se ha renombrado el grupo ', OLD.nombre, ' a ', NEW.nombre));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'grupos', NEW.id, CONCAT('Se ha actualizado el grupo ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_grupo
AFTER DELETE ON grupos
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'grupos', OLD.id, CONCAT('Se ha eliminado el grupo ', OLD.nombre));
END//

-- ========================
-- TRIGGERS PARA INVENTARIOS
-- ========================
CREATE TRIGGER after_insert_inventario
AFTER INSERT ON inventarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'inventarios', NEW.id, CONCAT('Se ha creado el inventario ', NEW.nombre));
END//

CREATE TRIGGER after_update_inventario
AFTER UPDATE ON inventarios
FOR EACH ROW
BEGIN
    -- Si solo cambió la fecha de modificación, no registrar
    IF OLD.fecha_modificacion != NEW.fecha_modificacion AND
       OLD.nombre = NEW.nombre AND
       OLD.responsable = NEW.responsable AND
       OLD.estado_conservacion = NEW.estado_conservacion AND
       OLD.grupo_id = NEW.grupo_id THEN
        -- No hacer nada, solo se actualizó la fecha
        SET @dummy = 0; -- Necesario para tener un bloque válido en MySQL
    -- Si cambió el nombre
    ELSEIF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'inventarios', NEW.id, CONCAT('Se ha renombrado el inventario ', OLD.nombre, ' a ', NEW.nombre));
    -- Si solo cambió el estado de conservación
    ELSEIF OLD.estado_conservacion != NEW.estado_conservacion THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'inventarios', NEW.id, CONCAT('El estado del inventario ', 
               NEW.nombre, ' pasó de ', OLD.estado_conservacion, ' a ', NEW.estado_conservacion));
    -- Para otros cambios
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'inventarios', NEW.id, CONCAT('Se ha editado el inventario ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_inventario
AFTER DELETE ON inventarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'inventarios', OLD.id, CONCAT('Se ha eliminado el inventario ', OLD.nombre));
END//

-- ========================
-- TRIGGERS PARA BIENES
-- ========================
CREATE TRIGGER after_insert_bien
AFTER INSERT ON bienes
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'bienes', NEW.id, CONCAT('Se ha creado el bien ', NEW.nombre, ' de tipo ', NEW.tipo));
END//

CREATE TRIGGER after_update_bien
AFTER UPDATE ON bienes
FOR EACH ROW
BEGIN
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes', NEW.id, CONCAT('Se ha renombrado el bien ', OLD.nombre, ' a ', NEW.nombre));
    ELSEIF OLD.imagen != NEW.imagen OR (OLD.imagen IS NULL AND NEW.imagen IS NOT NULL) THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes', NEW.id, CONCAT('Se ha actualizado la imagen del bien ', NEW.nombre));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes', NEW.id, CONCAT('Se ha actualizado el bien ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_bien
AFTER DELETE ON bienes
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'bienes', OLD.id, CONCAT('Se ha eliminado el bien ', OLD.nombre));
END//

-- ================================
-- TRIGGERS PARA BIENES_INVENTARIOS
-- ================================
CREATE TRIGGER after_insert_bien_inventario
AFTER INSERT ON bienes_inventarios
FOR EACH ROW
BEGIN
    DECLARE nombre_bien VARCHAR(100);
    DECLARE nombre_inventario VARCHAR(100);
    DECLARE tipo_bien ENUM('Cantidad', 'Serial');
    DECLARE cantidad_bien INT DEFAULT 1;
    
    -- Obtener el nombre del bien y su tipo
    SELECT b.nombre, b.tipo INTO nombre_bien, tipo_bien
    FROM bienes b
    WHERE b.id = NEW.bien_id;
    
    -- Obtener el nombre del inventario
    SELECT i.nombre INTO nombre_inventario
    FROM inventarios i
    WHERE i.id = NEW.inventario_id;
    
    -- Si es tipo cantidad, verificar si ya tiene un registro en bienes_cantidad
    IF tipo_bien = 'Cantidad' THEN
        SELECT IFNULL(bc.cantidad, 1) INTO cantidad_bien
        FROM bienes_cantidad bc
        WHERE bc.bien_inventario_id = NEW.id
        LIMIT 1;
    END IF;
    
    -- Insertar en historial con mensaje específico
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'bienes_inventarios', NEW.id, 
            CONCAT('Se añadieron ', cantidad_bien, ' ', nombre_bien, ' al inventario ', nombre_inventario));
END//

CREATE TRIGGER after_delete_bien_inventario
AFTER DELETE ON bienes_inventarios
FOR EACH ROW
BEGIN
    DECLARE nombre_bien VARCHAR(100);
    DECLARE nombre_inventario VARCHAR(100);
    
    -- Obtener el nombre del bien
    SELECT b.nombre INTO nombre_bien
    FROM bienes b
    WHERE b.id = OLD.bien_id;
    
    -- Obtener el nombre del inventario
    SELECT i.nombre INTO nombre_inventario
    FROM inventarios i
    WHERE i.id = OLD.inventario_id;
    
    -- Insertar en historial
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'bienes_inventarios', OLD.id, 
            CONCAT('Se eliminaron registros de ', nombre_bien, ' del inventario ', nombre_inventario));
END//

-- ================================
-- TRIGGERS PARA BIENES_CANTIDAD
-- ================================
CREATE TRIGGER after_update_bien_cantidad
AFTER UPDATE ON bienes_cantidad
FOR EACH ROW
BEGIN
    DECLARE nombre_bien VARCHAR(100);
    DECLARE nombre_inventario VARCHAR(100);
    
    -- Obtener el nombre del bien y del inventario
    SELECT b.nombre, i.nombre INTO nombre_bien, nombre_inventario
    FROM bienes_inventarios bi
    JOIN bienes b ON b.id = bi.bien_id
    JOIN inventarios i ON i.id = bi.inventario_id
    WHERE bi.id = NEW.bien_inventario_id;
    
    -- Insertar en historial
    IF OLD.cantidad != NEW.cantidad THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes_cantidad', NEW.id, 
                CONCAT('La cantidad de ', nombre_bien, ' en el inventario ', nombre_inventario, 
                       ' cambió de ', OLD.cantidad, ' a ', NEW.cantidad));
    END IF;
END//

CREATE TRIGGER after_delete_bien_cantidad
AFTER DELETE ON bienes_cantidad
FOR EACH ROW
BEGIN
    DECLARE nombre_bien VARCHAR(100);
    DECLARE nombre_inventario VARCHAR(100);
    
    -- Obtener el nombre del bien y del inventario
    SELECT b.nombre, i.nombre INTO nombre_bien, nombre_inventario
    FROM bienes_inventarios bi
    JOIN bienes b ON b.id = bi.bien_id
    JOIN inventarios i ON i.id = bi.inventario_id
    WHERE bi.id = OLD.bien_inventario_id;
    
    -- Insertar en historial
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'bienes_cantidad', OLD.id, 
            CONCAT('Se eliminó el registro de cantidad (', OLD.cantidad, ') para ', nombre_bien, 
                   ' del inventario ', nombre_inventario));
END//

-- ================================
-- TRIGGERS PARA BIENES_EQUIPOS (SERIAL)
-- ================================
CREATE TRIGGER after_update_bien_equipo
AFTER UPDATE ON bienes_equipos
FOR EACH ROW
BEGIN
    DECLARE nombre_bien VARCHAR(100);
    DECLARE nombre_inventario VARCHAR(100);
    
    -- Obtener el nombre del bien y del inventario
    SELECT b.nombre, i.nombre INTO nombre_bien, nombre_inventario
    FROM bienes_inventarios bi
    JOIN bienes b ON b.id = bi.bien_id
    JOIN inventarios i ON i.id = bi.inventario_id
    WHERE bi.id = NEW.bien_inventario_id;
    
    -- Insertar en historial
    IF OLD.estado != NEW.estado THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes_equipos', NEW.id, 
                CONCAT('El estado del equipo ', nombre_bien, ' (S/N: ', NEW.serial, ') en el inventario ', 
                       nombre_inventario, ' cambió de ', OLD.estado, ' a ', NEW.estado));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes_equipos', NEW.id, 
                CONCAT('Se actualizó la información del equipo ', nombre_bien, ' (S/N: ', 
                       NEW.serial, ') en el inventario ', nombre_inventario));
    END IF;
END//

CREATE TRIGGER after_delete_bien_equipo
AFTER DELETE ON bienes_equipos
FOR EACH ROW
BEGIN
    DECLARE nombre_bien VARCHAR(100);
    DECLARE nombre_inventario VARCHAR(100);
    
    -- Obtener el nombre del bien y del inventario
    SELECT b.nombre, i.nombre INTO nombre_bien, nombre_inventario
    FROM bienes_inventarios bi
    JOIN bienes b ON b.id = bi.bien_id
    JOIN inventarios i ON i.id = bi.inventario_id
    WHERE bi.id = OLD.bien_inventario_id;
    
    -- Insertar en historial
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'bienes_equipos', OLD.id, 
            CONCAT('Se eliminó el registro del equipo ', nombre_bien, ' (S/N: ', 
                   OLD.serial, ') del inventario ', nombre_inventario));
END//

-- ================================
-- TRIGGERS PARA CARPETAS_REPORTES
-- ================================
CREATE TRIGGER after_insert_carpeta_reporte
AFTER INSERT ON carpetas_reportes
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'carpetas_reportes', NEW.id, CONCAT('Se ha creado la carpeta ', NEW.nombre));
END//

CREATE TRIGGER after_update_carpeta_reporte
AFTER UPDATE ON carpetas_reportes
FOR EACH ROW
BEGIN
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'carpetas_reportes', NEW.id, CONCAT('Se ha renombrado la carpeta ', OLD.nombre, ' a ', NEW.nombre));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'carpetas_reportes', NEW.id, CONCAT('Se ha editado la carpeta ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_carpeta_reporte
AFTER DELETE ON carpetas_reportes
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'carpetas_reportes', OLD.id, CONCAT('Se ha eliminado la carpeta ', OLD.nombre));
END//

-- ================================
-- TRIGGERS PARA REPORTES
-- ================================
CREATE TRIGGER after_insert_reporte
AFTER INSERT ON reportes
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'reportes', NEW.id, CONCAT('Se ha creado el reporte ', NEW.nombre));
END//

CREATE TRIGGER after_update_reporte
AFTER UPDATE ON reportes
FOR EACH ROW
BEGIN
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'reportes', NEW.id, CONCAT('Se ha renombrado el reporte ', OLD.nombre, ' a ', NEW.nombre));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'reportes', NEW.id, CONCAT('Se ha editado el reporte ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_reporte
AFTER DELETE ON reportes
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'reportes', OLD.id, CONCAT('Se ha eliminado el reporte ', OLD.nombre));
END//

DELIMITER ;