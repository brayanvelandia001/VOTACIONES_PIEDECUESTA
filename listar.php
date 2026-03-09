<?php
include 'conexion.php';

// Traemos todos los campos necesarios, incluyendo barrio y dirección
$sql = "SELECT s.*, c.nombre as nombre_capitan 
        FROM simpatizantes s 
        LEFT JOIN simpatizantes c ON s.capitan_id = c.id 
        ORDER BY s.id DESC";

$res = $conn->query($sql);

if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        // Formateamos el texto del capitán
        $capitan = $row['nombre_capitan'] ? 
            "<br><small class='text-primary fw-bold'><i class='fas fa-chevron-right me-1'></i> Cap: {$row['nombre_capitan']}</small>" : 
            "";
        
        // Limpiamos los textos para evitar errores de comillas en el JavaScript
        $nombre_js = addslashes($row['nombre']);
        $lugar_js = addslashes($row['lugar_votacion']);
        $barrio_js = addslashes($row['barrio_vereda'] ?? '');
        $direccion_js = addslashes($row['direccion_residencia'] ?? '');

        echo "<tr>
                <td class='ps-3'><span class='fw-bold text-muted small'>{$row['cedula']}</span></td>
                <td>
                    <span class='fw-bold text-uppercase'>{$row['nombre']}</span>
                    $capitan
                </td>
                <td class='small text-secondary'>
                    {$row['lugar_votacion']}
                    <br><span class='badge bg-light text-dark border-0 p-0'><i class='fas fa-map-marker-alt text-danger'></i> {$row['barrio_vereda']}</span>
                </td>
                <td><span class='badge bg-light text-primary border px-3 py-2'>MESA {$row['mesa']}</span></td>
                <td class='small text-muted'><i class='fas fa-phone-alt me-1'></i> {$row['telefono']}</td>
                
                <td class='text-center'>
                    <button class='btn btn-white btn-sm rounded-circle shadow-sm border me-1' 
                            onclick=\"prepararEditar('{$row['id']}', '$nombre_js', '$lugar_js', '{$row['mesa']}', '{$row['telefono']}', '$barrio_js', '$direccion_js')\" 
                            title='Editar'>
                        <i class='fas fa-edit text-primary'></i>
                    </button>

                    <button class='btn btn-white btn-sm rounded-circle shadow-sm border' onclick='borrar({$row['id']})' title='Eliminar'>
                        <i class='fas fa-trash text-danger'></i>
                    </button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No hay registros todavía</td></tr>";
}
$conn->close();
?>