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


CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE INDEX idx_grupos_nombre ON grupos(nombre);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE inventarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    responsable INT,
    bloque VARCHAR(100),
    dependencia VARCHAR(100),
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado_conservacion ENUM('bueno', 'regular', 'malo') NOT NULL,
    grupo_id INT,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id) ON DELETE CASCADE,
    FOREIGN KEY (responsable) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE INDEX idx_inventarios_nombre ON inventarios(nombre);
CREATE INDEX idx_inventarios_grupo_id ON inventarios(grupo_id);
CREATE INDEX idx_inventarios_responsable ON inventarios(responsable);
CREATE INDEX idx_inventarios_estado_conservacion ON inventarios(estado_conservacion);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE tipos_bienes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE INDEX idx_tipos_bienes_nombre ON tipos_bienes(nombre);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo_bien_id INT NOT NULL,
    inventario_id INT NOT NULL,
    FOREIGN KEY (tipo_bien_id) REFERENCES tipos_bienes(id) ON DELETE CASCADE,
    FOREIGN KEY (inventario_id) REFERENCES inventarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_bienes_nombre ON bienes(nombre);
CREATE INDEX idx_bienes_tipo_bien_id ON bienes(tipo_bien_id);
CREATE INDEX idx_bienes_inventario_id ON bienes(inventario_id);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes_cantidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bien_id INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (bien_id) REFERENCES bienes(id) ON DELETE CASCADE
);

CREATE INDEX idx_bienes_cantidad_bien_id ON bienes_cantidad(bien_id);
CREATE INDEX idx_bienes_cantidad_cantidad ON bienes_cantidad(cantidad);

-- 
-- ---------------------------------------------------------
--


CREATE TABLE bienes_equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bien_id INT NOT NULL,
    descripcion TEXT,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    serial VARCHAR(100) UNIQUE,
    estado ENUM('activo', 'inactivo', 'en_mantenimiento') NOT NULL,
    color VARCHAR(100),
    condiciones_tecnicas TEXT,
    fecha_ingreso DATE,
    fecha_salida DATE,
    FOREIGN KEY (bien_id) REFERENCES bienes(id) ON DELETE CASCADE
);

CREATE INDEX idx_bienes_equipos_bien_id ON bienes_equipos(bien_id);
CREATE INDEX idx_bienes_equipos_serial ON bienes_equipos(serial);
CREATE INDEX idx_bienes_equipos_estado ON bienes_equipos(estado);
CREATE INDEX idx_bienes_equipos_fecha_ingreso ON bienes_equipos(fecha_ingreso);
CREATE INDEX idx_bienes_equipos_fecha_salida ON bienes_equipos(fecha_salida);

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


INSERT INTO tipos_bienes (nombre) VALUES ('Cantidad'), ('Equipo');