-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS crud_app;

-- Usar la base de datos creada
USE crud_app;

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad_en_stock INT NOT NULL
);