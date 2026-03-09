<?php
include 'conexion.php';

$sql = "SELECT lugar_votacion, COUNT(*) as total 
        FROM simpatizantes 
        GROUP BY lugar_votacion 
        ORDER BY total DESC";

$res = $conn->query($sql);

if ($res->num_rows > 0) {
    // Buscamos el máximo para calcular el porcentaje de las barras
    $max_res = $conn->query("SELECT COUNT(*) as maximo FROM simpatizantes GROUP BY lugar_votacion ORDER BY maximo DESC LIMIT 1");
    $max_row = $max_res->fetch_assoc();
    $maximo = $max_row['maximo'];

    while($row = $res->fetch_assoc()) {
        $porcentaje = ($row['total'] / $maximo) * 100;
        echo "<div class='analytics-item p-2 mb-2'>
                <div class='d-flex justify-content-between mb-1'>
                    <span class='small fw-bold text-uppercase'>{$row['lugar_votacion']}</span>
                    <span class='badge bg-primary'>{$row['total']}</span>
                </div>
                <div class='progress' style='height: 6px;'>
                    <div class='progress-bar bg-warning' style='width: {$porcentaje}%'></div>
                </div>
              </div>";
    }
} else {
    echo "<p class='text-muted small text-center py-3'>No hay datos para analizar.</p>";
}
$conn->close();
?>