-- Base de datos para Gestión de Residuos
-- Compatible con MariaDB

CREATE DATABASE IF NOT EXISTS db_gestionresiduos;
USE db_gestionresiduos;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    comunidad VARCHAR(100) NOT NULL,
    direccion TEXT NULL,
    numero_casa VARCHAR(20) NULL,
    telefono VARCHAR(50) NULL,
    foto_perfil VARCHAR(255) NULL,
    puntos INT DEFAULT 0 NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Tabla de reportes
CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    descripcion TEXT NOT NULL,
    foto VARCHAR(255) NULL,
    ubicacion VARCHAR(200) NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de recolecciones
CREATE TABLE recolecciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_residuo VARCHAR(100) NULL,
    estado VARCHAR(50) DEFAULT 'pendiente' NULL,
    fecha_solicitada DATE NULL,
    fecha_confirmada DATE NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de talleres
CREATE TABLE talleres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    fecha DATE NULL
);

-- Tabla de inscripciones a talleres
CREATE TABLE inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_taller INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_taller) REFERENCES talleres (id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de lugares en el mapa
CREATE TABLE lugares_mapa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    lat DECIMAL(10, 8) NOT NULL,
    lng DECIMAL(11, 8) NOT NULL,
    tipo VARCHAR(50) NOT NULL,  -- Ej: reciclaje, biodigestor, evento
    descripcion TEXT NULL
);

-- Índices para mejorar rendimiento
CREATE INDEX idx_reportes_id_usuario ON reportes (id_usuario);
CREATE INDEX idx_recolecciones_id_usuario ON recolecciones (id_usuario);
CREATE INDEX idx_inscripciones_id_usuario ON inscripciones (id_usuario);
CREATE INDEX idx_inscripciones_id_taller ON inscripciones (id_taller);