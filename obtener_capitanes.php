<?php
include 'conexion.php';

// Traemos solo ID y Nombre de todos para que sirvan como capitanes
$res = $conn->query("SELECT id, nombre FROM simpatizantes ORDER BY nombre ASC");

echo "<option value=''>-- Sin Capitán (Es Líder Independiente) --</option>";
while($row = $res->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>