<?php
include 'conexion.php';

// Consulta limpia para traer a todos
$res = $conn->query("SELECT id, nombre FROM simpatizantes ORDER BY nombre ASC");

if($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        // htmlspecialchars evita que nombres con tildes o comillas dañen el HTML
        $nombre = htmlspecialchars($row['nombre']);
        echo "<option value='{$row['id']}'>{$nombre}</option>";
    }
} else {
    echo "<option disabled>No hay registros aún</option>";
}
?>