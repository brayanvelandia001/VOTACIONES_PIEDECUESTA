<?php
include 'conexion.php';

// Esta consulta une la tabla consigo misma para traer el nombre del capitán
$sql = "SELECT s.*, c.nombre as nombre_capitan 
        FROM simpatizantes s 
        LEFT JOIN simpatizantes c ON s.capitan_id = c.id 
        ORDER BY s.id DESC";

$res = $conn->query($sql);

if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $capitan = $row['nombre_capitan'] ? 
            "<br><small class='text-primary fw-bold'><i class='fas fa-chevron-right me-1'></i> Cap: {$row['nombre_capitan']}</small>" : 
            "";

        echo "<tr>
                <td class='ps-3'><span class='fw-bold text-muted small'>{$row['cedula']}</span></td>
                <td>
                    <span class='fw-bold text-uppercase'>{$row['nombre']}</span>
                    $capitan
                </td>
                <td class='small text-secondary'>{$row['lugar_votacion']}</td>
                <td><span class='badge bg-light text-primary border px-3 py-2'>MESA {$row['mesa']}</span></td>
                <td class='text-center'>
                    <button class='btn btn-white btn-sm rounded-circle shadow-sm border' onclick='borrar({$row['id']})'>
                        <i class='fas fa-trash text-danger'></i>
                    </button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No hay registros todavía</td></tr>";
}
$conn->close();
?>