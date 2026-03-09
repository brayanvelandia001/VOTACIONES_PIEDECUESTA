<?php
include 'conexion.php';

$sql = "SELECT c.nombre, COUNT(s.id) as total 
        FROM simpatizantes s 
        JOIN simpatizantes c ON s.capitan_id = c.id 
        GROUP BY c.id 
        ORDER BY total DESC 
        LIMIT 5";

$res = $conn->query($sql);

if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        echo "<div class='analytics-item p-2 mb-2'>
                <div class='d-flex justify-content-between mb-1'>
                    <span class='small fw-bold text-uppercase text-truncate' style='max-width:150px;'>{$row['nombre']}</span>
                    <span class='badge bg-indigo text-white'>{$row['total']} referidos</span>
                </div>
              </div>";
    }
} else {
    echo "<p class='text-muted small text-center'>Aún no hay capitanes con referidos.</p>";
}
$conn->close();
?>