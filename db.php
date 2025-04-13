<?php

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configuración de la base de datos
$host = $_ENV['MYSQL_HOST'];
$username = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];
$database = $_ENV['MYSQL_DATABASE'];

// Conexión a la base de datos
$mysqli = new mysqli($host, $username, $password, $database);

// Comprobar la conexión
if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error);
}

return $mysqli;
