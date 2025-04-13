SET NAMES utf8mb4;

-- Crear base de datos de la API
CREATE DATABASE IF NOT EXISTS sample_api;

-- Crear tabla de productos
CREATE TABLE IF NOT EXISTS sample_api.product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inicializar la tabla de productos con algunos datos
INSERT INTO sample_api.product (name, price) VALUES ('Product 1', 10.99);
INSERT INTO sample_api.product (name, price) VALUES ('Product 2', 20.99);
INSERT INTO sample_api.product (name, price) VALUES ('Product 3', 30.99);

-- Crear base de datos de autenticación
CREATE DATABASE IF NOT EXISTS auth_api;

-- Crear bases de datos de testing
CREATE DATABASE IF NOT EXISTS sample_api_test;
CREATE DATABASE IF NOT EXISTS auth_api_test;

-- Otorgar permisos al usuario
GRANT ALL PRIVILEGES ON auth_api.* TO 'example'@'%';
GRANT ALL PRIVILEGES ON sample_api_test.* TO 'example'@'%';
GRANT ALL PRIVILEGES ON auth_api_test.* TO 'example'@'%';
FLUSH PRIVILEGES;