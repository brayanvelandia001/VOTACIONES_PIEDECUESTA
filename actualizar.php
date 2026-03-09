<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'];
    $nombre   = strtoupper($_POST['nombre']);
    $lugar    = $_POST['lugar'];
    $mesa     = $_POST['mesa'];
    $tel      = $_POST['telefono'];
    $barrio   = $_POST['barrio_vereda'] ?? '';
    $direccion = strtoupper($_POST['direccion_residencia'] ?? '');

    // 7 campos en total para actualizar
    $stmt = $conn->prepare("UPDATE simpatizantes SET 
                            nombre=?, 
                            lugar_votacion=?, 
                            barrio_vereda=?, 
                            direccion_residencia=?, 
                            mesa=?, 
                            telefono=? 
                            WHERE id=?");
    
    // "ssssisi" -> 4 strings, 1 entero (mesa), 1 string (tel), 1 entero (id)
    $stmt->bind_param("ssssisi", $nombre, $lugar, $barrio, $direccion, $mesa, $tel, $id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
$conn->close();
?>