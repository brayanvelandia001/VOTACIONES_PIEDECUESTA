<?php
// Detectar si estamos en local (XAMPP) o en Clever Cloud
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "votaciones";
} else {
    // Estos datos los sacaremos de Clever Cloud después
    $host = getenv("MYSQL_ADDON_HOST");
    $user = getenv("MYSQL_ADDON_USER");
    $pass = getenv("MYSQL_ADDON_PASSWORD");
    $db   = getenv("MYSQL_ADDON_DB");
    $port = getenv("MYSQL_ADDON_PORT");
}

$conn = new mysqli($host, $user, $pass, $db, isset($port) ? $port : 3306);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>