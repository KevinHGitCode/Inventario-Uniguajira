CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'consultor') NOT NULL
);

CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

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

CREATE TABLE tipos_bienes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE bienes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo_bien_id INT NOT NULL,
    inventario_id INT NOT NULL,
    FOREIGN KEY (tipo_bien_id) REFERENCES tipos_bienes(id) ON DELETE CASCADE,
    FOREIGN KEY (inventario_id) REFERENCES inventarios(id) ON DELETE CASCADE
);

CREATE TABLE bienes_cantidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bien_id INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (bien_id) REFERENCES bienes(id) ON DELETE CASCADE
);

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

INSERT INTO tipos_bienes (nombre) VALUES ('Cantidad'), ('Equipo');