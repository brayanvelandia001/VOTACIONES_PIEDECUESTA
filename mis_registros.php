<?php
include 'conexion.php';
session_start();

$mi_id = $_SESSION['usuario_id'];
$conn->set_charset("utf8");

// 1. Agregamos s.mesa y s.telefono a la selección
$sql = "SELECT s.cedula, s.nombre, s.lugar_votacion, s.mesa, s.telefono, c.nombre AS nombre_capitan 
        FROM simpatizantes s 
        LEFT JOIN simpatizantes c ON s.capitan_id = c.id 
        WHERE s.registrado_por = ? 
        ORDER BY s.id DESC LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mi_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $capitan = !empty($row['nombre_capitan']) ? $row['nombre_capitan'] : '<span class="text-muted">Directo</span>';
        
        // 2. Imprimimos las celdas en el orden: Cédula, Nombre, Puesto, Mesa, Teléfono, Capitán
        echo "<tr>
                <td class='small'>".$row['cedula']."</td>
                <td class='small text-uppercase fw-bold'>".$row['nombre']."</td>
                <td class='small'>".$row['lugar_votacion']."</td>
                <td class='small'>".$row['mesa']."</td>
                <td class='small'>".$row['telefono']."</td>
                <td class='small text-primary'>".$capitan."</td>
              </tr>";
    }
} else {
    // 3. Ajustamos el colspan a 6
    echo "<tr><td colspan='6' class='text-center text-muted small'>Aún no has hecho registros.</td></tr>";
}
?>