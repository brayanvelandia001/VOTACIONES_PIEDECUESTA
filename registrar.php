<?php
session_start(); // Importante para saber quién registra
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capturamos los datos que ya tenías
    $cedula  = $_POST['cedula'];
    $nombre  = strtoupper($_POST['nombre']);
    $lugar   = $_POST['lugar'];
    $mesa    = $_POST['mesa'];
    $tel     = $_POST['telefono'];
    
    // 2. AGREGAMOS LOS DOS CAMPOS NUEVOS
    $barrio    = $_POST['barrio_vereda'] ?? '';
    $direccion = strtoupper($_POST['direccion_residencia'] ?? '');
    
    // 3. Datos de control
    $registrado_por = $_SESSION['usuario_id'] ?? null; 
    $capitan = !empty($_POST['capitan_id']) ? $_POST['capitan_id'] : null;

    // 4. Preparamos el SQL con las nuevas columnas (Asegúrate que se llamen así en tu DB)
    // Agregamos: barrio_vereda, direccion_residencia y registrado_por
    $sql = "INSERT INTO simpatizantes 
            (cedula, nombre, lugar_votacion, barrio_vereda, direccion_residencia, mesa, telefono, capitan_id, registrado_por) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    

    $stmt->bind_param("sssssissi", $cedula, $nombre, $lugar, $barrio, $direccion, $mesa, $tel, $capitan, $registrado_por);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        // Si falla, esto te dirá por qué (ej: columna mal escrita)
        echo "error: " . $conn->error;
    }
    
    $stmt->close();
}
$conn->close();
?>