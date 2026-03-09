<?php
$conn = new mysqli("localhost", "root", "TU_PASSWORD", "politica_local");

$res = $conn->query("SELECT * FROM simpatizantes ORDER BY id DESC");

while($row = $res->fetch_assoc()) {
    echo "<tr>
            <td><b>{$row['cedula']}</b></td>
            <td>{$row['nombre']}</td>
            <td>{$row['lugar_votacion']}</td>
            <td><span class='badge bg-primary'>{$row['mesa']}</span></td>
          </tr>";
}
$conn->close();
?>