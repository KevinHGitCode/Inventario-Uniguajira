-- 
-- SQUEMA DE BASE DE DATOS
-- 

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'consultor') NOT NULL
);

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
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_tareas_usuario_id ON tareas(usuario_id);
CREATE INDEX idx_tareas_fecha ON tareas(fecha);
CREATE INDEX idx_tareas_fecha_creacion ON tareas(fecha_creacion);
CREATE INDEX idx_tareas_fecha_completado ON tareas(fecha_completado);

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

-- Crear la tabla bloques
CREATE TABLE bloques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear índice para bloques
CREATE INDEX idx_bloques_nombre ON bloques(nombre);

-- Crear la tabla dependencias
CREATE TABLE dependencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Crear índice para dependencias
CREATE INDEX idx_dependencias_nombre ON dependencias(nombre);

-- Crear la nueva tabla inventarios
CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    responsable_id INT,
    bloque_id INT,
    dependencia_id INT,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado_conservacion ENUM('bueno', 'regular', 'malo') NOT NULL,
    grupo_id INT,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES responsables(id) ON DELETE SET NULL,
    FOREIGN KEY (bloque_id) REFERENCES bloques(id) ON DELETE SET NULL,
    FOREIGN KEY (dependencia_id) REFERENCES dependencias(id) ON DELETE SET NULL
);

-- Crear índices para la tabla inventarios
CREATE INDEX idx_inventarios_nombre ON inventarios(nombre);
CREATE INDEX idx_inventarios_grupo_id ON inventarios(grupo_id);
CREATE INDEX idx_inventarios_responsable_id ON inventarios(responsable_id);
CREATE INDEX idx_inventarios_bloque_id ON inventarios(bloque_id);
CREATE INDEX idx_inventarios_dependencia_id ON inventarios(dependencia_id);
CREATE INDEX idx_inventarios_estado_conservacion ON inventarios(estado_conservacion);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('Cantidad', 'Serial') NOT NULL  -- ENUM para tipos de bienes
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

