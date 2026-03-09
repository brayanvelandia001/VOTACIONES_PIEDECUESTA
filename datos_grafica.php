<?php
include 'conexion.php';

// Contamos simpatizantes agrupados por la zona (Comuna/Corregimiento)
// Si no usas la columna comuna, agrupamos por los barrios principales
$sql = "SELECT barrio_vereda, COUNT(*) as total 
        FROM simpatizantes 
        GROUP BY barrio_vereda 
        ORDER BY total DESC 
        LIMIT 10"; // Los 10 barrios con más gente

$res = $conn->query($sql);
$labels = [];
$counts = [];

while($row = $res->fetch_assoc()){
    $labels[] = $row['barrio_vereda'];
    $counts[] = (int)$row['total'];
}

echo json_encode(['labels' => $labels, 'counts' => $counts]);