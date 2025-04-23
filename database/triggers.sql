-- 
-- TRIGGERS (DISPARADORES) PARA LLEVAR EL HISTORIAL
-- 

--
-- --------------------------------------------------------------------
-- Ejemplos prácticos: En PHP podría ser algo así:
--
-- $userId = $_SESSION['user_id']; // Obtener ID del usuario de la sesión
-- $conn->query("SET @usuario_actual = $userId");  <<---
--
-- // Ahora ejecutar la operación que dispara el trigger
-- $conn->query("DELETE FROM usuarios WHERE id = $someId");
-- --------------------------------------------------------------------
--


-- Triggers para la tabla 'usuarios'
DELIMITER //

CREATE TRIGGER after_insert_usuario
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (NEW.id, 'INSERT', 'usuarios', NEW.id, CONCAT('Se ha creado el usuario ', NEW.nombre_usuario));
END//

CREATE TRIGGER after_update_usuario
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF OLD.nombre_usuario != NEW.nombre_usuario THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (NEW.id, 'UPDATE', 'usuarios', NEW.id, CONCAT('Se ha actualizado el usuario ', OLD.nombre_usuario, ' a ', NEW.nombre_usuario));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (NEW.id, 'UPDATE', 'usuarios', NEW.id, CONCAT('Se ha actualizado el usuario ', NEW.nombre_usuario));
    END IF;
END//

CREATE TRIGGER after_delete_usuario
AFTER DELETE ON usuarios
FOR EACH ROW
BEGIN
    -- Aquí usarías una variable de sesión para saber quién está realizando la acción
    -- ya que el usuario eliminado no puede registrar su propia eliminación
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'usuarios', OLD.id, CONCAT('Se ha eliminado el usuario ', OLD.nombre_usuario));
END//

-- Triggers para la tabla 'tareas'
CREATE TRIGGER after_insert_tarea
AFTER INSERT ON tareas
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (NEW.usuario_id, 'INSERT', 'tareas', NEW.id, CONCAT('Se ha creado la tarea ', NEW.nombre));
END//

CREATE TRIGGER after_update_tarea
AFTER UPDATE ON tareas
FOR EACH ROW
BEGIN
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (NEW.usuario_id, 'UPDATE', 'tareas', NEW.id, CONCAT('Se ha renombrado la tarea ', OLD.nombre, ' a ', NEW.nombre));
    ELSEIF OLD.estado != NEW.estado THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (NEW.usuario_id, 'UPDATE', 'tareas', NEW.id, CONCAT('Se ha actualizado el estado de la tarea ', NEW.nombre, ' de ', OLD.estado, ' a ', NEW.estado));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (NEW.usuario_id, 'UPDATE', 'tareas', NEW.id, CONCAT('Se ha actualizado la tarea ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_tarea
AFTER DELETE ON tareas
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'tareas', OLD.id, CONCAT('Se ha eliminado la tarea ', OLD.nombre));
END//

-- Triggers para la tabla 'grupos'
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

-- Triggers para la tabla 'inventarios'
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
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'inventarios', NEW.id, CONCAT('Se ha renombrado el inventario ', OLD.nombre, ' a ', NEW.nombre));
    ELSEIF OLD.estado_conservacion != NEW.estado_conservacion THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'inventarios', NEW.id, CONCAT('Se ha actualizado el estado de conservación del inventario ', 
               NEW.nombre, ' de ', OLD.estado_conservacion, ' a ', NEW.estado_conservacion));
    ELSE
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'inventarios', NEW.id, CONCAT('Se ha actualizado el inventario ', NEW.nombre));
    END IF;
END//

CREATE TRIGGER after_delete_inventario
AFTER DELETE ON inventarios
FOR EACH ROW
BEGIN
    INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
    VALUES (@usuario_actual, 'DELETE', 'inventarios', OLD.id, CONCAT('Se ha eliminado el inventario ', OLD.nombre));
END//

-- Triggers para la tabla 'bienes'
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
    ELSEIF OLD.tipo != NEW.tipo THEN
        INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles)
        VALUES (@usuario_actual, 'UPDATE', 'bienes', NEW.id, CONCAT('Se ha actualizado el tipo del bien ', 
               NEW.nombre, ' de ', OLD.tipo, ' a ', NEW.tipo));
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

DELIMITER ;