-- 
-- TRIGGERS MEJORADOS (DISPARADORES) PARA LLEVAR EL HISTORIAL
-- 

-- Nota: Todos los triggers asumen que la variable @usuario_actual está configurada
-- antes de realizar operaciones en la base de datos
-- Ejemplo en PHP:
-- $userId = $_SESSION['user_id']; 
-- $conn->query("SET @usuario_actual = $userId");

-- Primer administrador
INSERT INTO usuarios (nombre, nombre_usuario, email, contraseña, rol, foto_perfil)
VALUES 
    ('Administrador', 'admin', 'admin@email.com', '$2y$10$DY06BnTlLyr8z0b/IDjTXuN2pRNL9rCt0zYn0ZBDAsgjZsBmF3Hqq', 'administrador', NULL); -- Contraseña: admin

SET @usuario_actual = 1;


DELIMITER //

-- ========================
-- TRIGGERS PARA USUARIOS
-- ========================
CREATE TRIGGER after_insert_usuario
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    DECLARE usuario_creador VARCHAR(100);
    DECLARE mensaje VARCHAR(255);
    
    -- Obtener el nombre de usuario del creador
    SELECT nombre_usuario INTO usuario_creador 
    FROM usuarios 
    WHERE id = @usuario_actual;
    
    -- Si el usuario se creó a sí mismo (registro)
    IF @usuario_actual = NEW.id THEN
        SET mensaje = CONCAT('El usuario ', NEW.nombre_usuario, ' se ha registrado en el sistema');
    ELSE
        SET mensaje = CONCAT('El usuario ', usuario_creador, ' ha creado la cuenta de ', NEW.nombre_usuario);
    END IF;
    
    -- Insertar en el historial
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'usuarios', NEW.id, mensaje);
END//


CREATE TRIGGER after_update_usuario
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    DECLARE usuario_editor VARCHAR(100);
    DECLARE usuario_editado VARCHAR(100);
    DECLARE mensaje VARCHAR(255);
    DECLARE solo_acceso BOOLEAN;
    
    -- Determinar si SOLO cambió la fecha de último acceso
    SET solo_acceso = (
        OLD.nombre <=> NEW.nombre AND 
        OLD.nombre_usuario <=> NEW.nombre_usuario AND 
        OLD.email <=> NEW.email AND 
        OLD.contraseña <=> NEW.contraseña AND 
        OLD.rol <=> NEW.rol AND 
        OLD.foto_perfil <=> NEW.foto_perfil AND
        OLD.fecha_ultimo_acceso <> NEW.fecha_ultimo_acceso
    );
    
    -- Obtener el nombre de usuario del editor (quien realiza la acción)
    SELECT nombre_usuario INTO usuario_editor 
    FROM usuarios 
    WHERE id = @usuario_actual;
    
    -- Si no se puede obtener el nombre de usuario, usar el ID
    IF usuario_editor IS NULL THEN
        SET usuario_editor = CONCAT('ID:', @usuario_actual);
    END IF;
    
    -- Obtener el nombre de usuario del editado
    SET usuario_editado = NEW.nombre_usuario;
    
    -- Caso 1: Solo se actualizó la fecha de último acceso (inicio de sesión)
    IF solo_acceso THEN
        SET mensaje = CONCAT('El usuario ', usuario_editado, ' ha iniciado sesión');
    
    -- Caso 2: El usuario actualizó su propio perfil
    ELSEIF @usuario_actual = NEW.id THEN
        SET mensaje = CONCAT('El usuario ', usuario_editor, ' ha actualizado su perfil');
    
    -- Caso 3: Un usuario modificó los datos de otro usuario
    ELSE
        SET mensaje = CONCAT('El usuario ', usuario_editor, ' ha modificado los datos de ', usuario_editado);
    END IF;
    
    -- Insertar en el historial
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'UPDATE', 'usuarios', NEW.id, mensaje);
END//


CREATE TRIGGER after_delete_usuario
AFTER DELETE ON usuarios
FOR EACH ROW
BEGIN
    DECLARE usuario_eliminador VARCHAR(100);
    DECLARE mensaje VARCHAR(255);
    
    -- Obtener el nombre de usuario del eliminador
    SELECT nombre_usuario INTO usuario_eliminador 
    FROM usuarios 
    WHERE id = @usuario_actual;
    
    -- Si el usuario se eliminó a sí mismo (poco común pero posible)
    IF @usuario_actual = OLD.id THEN
        SET mensaje = CONCAT('El usuario ', OLD.nombre_usuario, ' ha eliminado su cuenta');
    ELSE
        SET mensaje = CONCAT('El usuario ', usuario_eliminador, ' ha eliminado la cuenta de ', OLD.nombre_usuario);
    END IF;
    
    -- Insertar en el historial
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'usuarios', OLD.id, mensaje);
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
    
    -- Obtener el nombre del bien y su tipo
    SELECT b.nombre, b.tipo INTO nombre_bien, tipo_bien
    FROM bienes b
    WHERE b.id = NEW.bien_id;
    
    -- Obtener el nombre del inventario
    SELECT i.nombre INTO nombre_inventario
    FROM inventarios i
    WHERE i.id = NEW.inventario_id;
    
    -- NO insertar en historial aquí, se hará desde los triggers de bienes_cantidad o bienes_equipos
    -- Solo guardamos un registro temporal si es necesario para debugging
    -- SET @ultimo_bien_inventario_id = NEW.id;
    -- SET @ultimo_nombre_bien = nombre_bien;
    -- SET @ultimo_nombre_inventario = nombre_inventario;
    -- SET @ultimo_tipo_bien = tipo_bien;
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
            CONCAT('Se eliminaron todos los registros de ', nombre_bien, ' del inventario ', nombre_inventario));
END//

-- ================================
-- TRIGGERS PARA BIENES_CANTIDAD
-- ================================

CREATE TRIGGER after_insert_bien_cantidad
AFTER INSERT ON bienes_cantidad
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
    
    -- Insertar en historial el verdadero registro de la acción
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'bienes_inventarios', NEW.bien_inventario_id, 
            CONCAT('Se añadieron ', NEW.cantidad, ' ', nombre_bien, ' al inventario ', nombre_inventario));
END//

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

CREATE TRIGGER after_insert_bien_equipo
AFTER INSERT ON bienes_equipos
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
    
    -- Insertar en historial el verdadero registro de la acción
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'INSERT', 'bienes_inventarios', NEW.bien_inventario_id, 
            CONCAT('Se añadió equipo ', nombre_bien, ' (S/N: ', 
                   IFNULL(NEW.serial, 'Sin serial'), ') al inventario ', nombre_inventario));
END//

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
                CONCAT('El estado del equipo ', nombre_bien, ' (S/N: ', IFNULL(NEW.serial, 'Sin serial'), ') en el inventario ', 
                       nombre_inventario, ' cambió de ', OLD.estado, ' a ', NEW.estado));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes_equipos', NEW.id, 
                CONCAT('Se actualizó la información del equipo ', nombre_bien, ' (S/N: ', 
                       IFNULL(NEW.serial, 'Sin serial'), ') en el inventario ', nombre_inventario));
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
                   IFNULL(OLD.serial, 'Sin serial'), ') del inventario ', nombre_inventario));
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