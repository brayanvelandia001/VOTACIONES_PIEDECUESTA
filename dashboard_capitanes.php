<?php
include 'conexion.php';
$conn->set_charset("utf8");

// LA MAGIA: Hacemos un JOIN de la tabla simpatizantes consigo misma (Self Join)
// 's' es el simpatizante referido, 'c' es el capitán
$sql = "SELECT c.nombre as capitan_nombre, COUNT(s.id) as total_votos 
        FROM simpatizantes s 
        JOIN simpatizantes c ON s.capitan_id = c.id 
        WHERE s.capitan_id IS NOT NULL 
        GROUP BY s.capitan_id 
        ORDER BY total_votos DESC 
        LIMIT 10";

$result = $conn->query($sql);
$puesto = 1;

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $color = ($puesto == 1) ? 'bg-warning text-dark' : 'bg-primary text-white';
        $icono = ($puesto == 1) ? '?? ' : '#'.$puesto.' ';
        
        echo '
        <div class="analytics-item mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold small">' .strtoupper($row['capitan_nombre']) . '</span>
                <span class="badge ' . $color . ' rounded-pill">' . $row['total_votos'] . ' VOTOS</span>
            </div>
            <div class="progress" style="height: 8px; border-radius: 10px;">
                <div class="progress-bar ' . ($puesto == 1 ? 'bg-warning' : 'bg-primary') . '" 
                     role="progressbar" 
                     style="width: 100%"></div>
            </div>
        </div>';
        $puesto++;
    }
} else {
    echo '<div class="text-center py-3 text-muted small">No hay votos vinculados a capitanes.</div>';
}
?>