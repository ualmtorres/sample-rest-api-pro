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

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS auth_api.users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Crear tabla de roles
CREATE TABLE IF NOT EXISTS auth_api.roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla pivot usuarios-roles
CREATE TABLE IF NOT EXISTS auth_api.user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES auth_api.users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES auth_api.roles(id) ON DELETE CASCADE
);

-- Crear tabla de tokens de refresco
CREATE TABLE IF NOT EXISTS auth_api.refresh_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES auth_api.users(id) ON DELETE CASCADE
);

-- Insertar roles básicos
INSERT IGNORE INTO auth_api.roles (name, description) VALUES
    ('admin', 'Administrador del sistema'),
    ('user', 'Usuario normal');

-- Crear índices
CREATE INDEX idx_users_username ON auth_api.users(username);
CREATE INDEX idx_users_email ON auth_api.users(email);
CREATE INDEX idx_refresh_tokens_token ON auth_api.refresh_tokens(token);
