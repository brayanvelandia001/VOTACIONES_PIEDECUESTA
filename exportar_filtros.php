<?php
include 'conexion.php';

// Configuraciones para que Excel reconozca tildes (Ñ y tildes de Piedecuesta)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Reporte_Piedecuesta_".date('Y-m-d').".xls");
header("Pragma: no-cache");
header("Expires: 0");

$capitan_id = $_POST['capitan'] ?? '';
$lugar_filtro = $_POST['lugar'] ?? ''; 
$mesa       = $_POST['mesa'] ?? '';
$comuna      = $_POST['comuna'] ?? '';

// SQL con el LEFT JOIN para traer el nombre del Capitán
$sql = "SELECT s.*, c.nombre AS nombre_capitan_real 
        FROM simpatizantes s 
        LEFT JOIN simpatizantes c ON s.capitan_id = c.id 
        WHERE 1=1";

if (!empty($capitan_id))   { $sql .= " AND s.capitan_id = '$capitan_id'"; }
if (!empty($lugar_filtro)) { $sql .= " AND s.lugar_votacion = '$lugar_filtro'"; }
if (!empty($mesa))         { $sql .= " AND s.mesa = '$mesa'"; }
if (!empty($comuna))       { $sql .= " AND s.comuna_corregimiento = '$comuna'"; }

$sql .= " ORDER BY s.nombre ASC";

$result = $conn->query($sql);

if (!$result) {
    die("Error en SQL: " . $conn->error);
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<table border="1">
    <tr style="background-color: #1a237e; color: white; font-weight: bold; text-align: center;">
        <th colspan="8" style="font-size: 18px; padding: 10px;">ESTRUCTURA ELECTORAL PIEDECUESTA - REPORTE FILTRADO</th>
    </tr>
    <tr style="background-color: #ffd600; color: black; font-weight: bold; text-align: center;">
        <th>CEDULA</th>
        <th>NOMBRE COMPLETO</th>
        <th>COMUNA / ZONA</th>
        <th>BARRIO / VEREDA</th>
        <th>PUESTO DE VOTACION</th>
        <th>MESA</th>
        <th>TELEFONO</th>
        <th>CAPITAN / REFERIDO</th>
    </tr>

    <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td style="vnd.ms-excel.numberformat:@; text-align: left;"><?php echo $row['cedula']; ?></td>
            <td><?php echo strtoupper($row['nombre']); ?></td>
            <td><?php echo strtoupper($row['comuna_corregimiento'] ?? 'N/A'); ?></td>
            <td><?php echo strtoupper($row['barrio_vereda'] ?? 'N/A'); ?></td>
            <td><?php echo $row['lugar_votacion']; ?></td>
            <td style="text-align: center;"><?php echo $row['mesa']; ?></td>
            <td><?php echo $row['telefono']; ?></td>
            <td style="color: #1a237e; font-weight: bold;">
                <?php echo !empty($row['nombre_capitan_real']) ? strtoupper($row['nombre_capitan_real']) : 'DIRECTO / SIN CAPITÁN'; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" style="text-align: center; color: red; font-weight: bold; padding: 20px;">
                No se encontraron registros con los filtros seleccionados.
            </td>
        </tr>
    <?php endif; ?>
</table>