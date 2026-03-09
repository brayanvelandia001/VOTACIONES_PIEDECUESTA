<?php
$host = "localhost";
$user = "root"; 
$pass = "123456"; 
$db   = "politica_local";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Esto es para que los nombres con tildes o Ñ se vean bien
$conn->set_charset("utf8mb4");
?>