<?php
include 'conexion.php';
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=lista_electoral.xls");

echo "Cedula\tNombre\tLugar Votacion\tMesa\tTelefono\n";

$res = $conn->query("SELECT * FROM simpatizantes");
while($row = $res->fetch_assoc()) {
    echo "{$row['cedula']}\t{$row['nombre']}\t{$row['lugar_votacion']}\t{$row['mesa']}\t{$row['telefono']}\n";
}
?>