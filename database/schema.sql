-- 
-- SQUEMA DE BASE DE DATOS
-- 

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    nombre_usuario VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'consultor') NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_ultimo_acceso TIMESTAMP NULL,
    foto_perfil VARCHAR(255) -- Campo para almacenar la ruta de la imagen de perfil
);

CREATE INDEX idx_usuarios_nombre_usuario ON usuarios(nombre_usuario);
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_rol ON usuarios(rol);

-- 
-- ---------------------------------------------------------
--

CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha DATE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_completado TIMESTAMP,
    estado ENUM('por hacer', 'completado') NOT NULL DEFAULT 'por hacer',
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_tareas_usuario_id ON tareas(usuario_id);
CREATE INDEX idx_tareas_fecha ON tareas(fecha);
CREATE INDEX idx_tareas_fecha_creacion ON tareas(fecha_creacion);
CREATE INDEX idx_tareas_fecha_completado ON tareas(fecha_completado);
CREATE INDEX idx_tareas_estado ON tareas(estado);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE INDEX idx_grupos_nombre ON grupos(nombre);

-- 
-- ---------------------------------------------------------
--


-- Crear la tabla responsables
CREATE TABLE responsables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear índice para responsables
CREATE INDEX idx_responsables_nombre ON responsables(nombre);

-- Crear la nueva tabla inventarios
CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    responsable_id INT,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado_conservacion ENUM('bueno', 'regular', 'malo') NOT NULL,
    grupo_id INT,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES responsables(id) ON DELETE SET NULL
);

-- Crear índices para la tabla inventarios
CREATE INDEX idx_inventarios_nombre ON inventarios(nombre);
CREATE INDEX idx_inventarios_grupo_id ON inventarios(grupo_id);
CREATE INDEX idx_inventarios_responsable_id ON inventarios(responsable_id);
CREATE INDEX idx_inventarios_estado_conservacion ON inventarios(estado_conservacion);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('Cantidad', 'Serial') NOT NULL,  -- ENUM para tipos de bienes
    imagen VARCHAR(255)  -- Campo para almacenar la ruta de la imagen
);

CREATE INDEX idx_bienes_nombre ON bienes(nombre);
CREATE INDEX idx_bienes_tipo ON bienes(tipo);  -- Índice para el campo ENUM

-- 
-- ---------------------------------------------------------
--

-- Tabla intermedia para la relación muchos a muchos entre bienes e inventarios
CREATE TABLE bienes_inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bien_id INT NOT NULL,
    inventario_id INT NOT NULL,
    FOREIGN KEY (bien_id) REFERENCES bienes(id) ON DELETE CASCADE,
    FOREIGN KEY (inventario_id) REFERENCES inventarios(id) ON DELETE CASCADE,
    UNIQUE KEY (bien_id, inventario_id) -- Evita duplicados en la relación
);

CREATE INDEX idx_bienes_inventarios_bien_id ON bienes_inventarios(bien_id);
CREATE INDEX idx_bienes_inventarios_inventario_id ON bienes_inventarios(inventario_id);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes_cantidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bien_inventario_id INT NOT NULL,  -- Relación con bienes_inventarios
    cantidad INT NOT NULL,
    FOREIGN KEY (bien_inventario_id) REFERENCES bienes_inventarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_bienes_cantidad_bien_inventario_id ON bienes_cantidad(bien_inventario_id);
CREATE INDEX idx_bienes_cantidad_cantidad ON bienes_cantidad(cantidad);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes_equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bien_inventario_id INT NOT NULL,  -- Relación con bienes_inventarios
    descripcion TEXT,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    serial VARCHAR(100) UNIQUE,
    estado ENUM('activo', 'inactivo', 'en_mantenimiento') NOT NULL,
    color VARCHAR(100),
    condiciones_tecnicas TEXT,
    fecha_ingreso DATE,
    fecha_salida DATE,
    FOREIGN KEY (bien_inventario_id) REFERENCES bienes_inventarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_bienes_equipos_bien_inventario_id ON bienes_equipos(bien_inventario_id);
CREATE INDEX idx_bienes_equipos_serial ON bienes_equipos(serial);
CREATE INDEX idx_bienes_equipos_estado ON bienes_equipos(estado);
CREATE INDEX idx_bienes_equipos_fecha_ingreso ON bienes_equipos(fecha_ingreso);
CREATE INDEX idx_bienes_equipos_fecha_salida ON bienes_equipos(fecha_salida);


-- 
-- ---------------------------------------------------------
--

-- Tabla para organizar reportes en carpetas
CREATE TABLE carpetas_reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear índices para la tabla carpetas_reportes
CREATE INDEX idx_carpetas_reportes_nombre ON carpetas_reportes(nombre);
CREATE INDEX idx_carpetas_reportes_fecha_creacion ON carpetas_reportes(fecha_creacion);

-- Tabla para almacenar reportes generados
CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carpeta_id INT,  -- Relación con carpetas_reportes
    nombre_inventario VARCHAR(100) NOT NULL,
    responsable VARCHAR(100),
    estado_inventario ENUM('bueno', 'regular', 'malo') NOT NULL,
    numero_total_bienes INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT,  -- Contenido detallado del reporte
    FOREIGN KEY (carpeta_id) REFERENCES carpetas_reportes(id) ON DELETE SET NULL
);

-- Crear índices para la tabla reportes
CREATE INDEX idx_reportes_carpeta_id ON reportes(carpeta_id);
CREATE INDEX idx_reportes_nombre_inventario ON reportes(nombre_inventario);
CREATE INDEX idx_reportes_responsable ON reportes(responsable);
CREATE INDEX idx_reportes_estado_inventario ON reportes(estado_inventario);
CREATE INDEX idx_reportes_fecha_creacion ON reportes(fecha_creacion);

-- 
-- ---------------------------------------------------------
-- VISTAS (TABLAS VIRTUALES) PARA OPTIMIZAR CONSULTAS


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
