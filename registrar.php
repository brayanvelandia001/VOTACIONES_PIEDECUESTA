<?php
// 1. Iniciamos sesión para poder leer quién está logueado
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula  = $_POST['cedula'];
    $nombre  = strtoupper($_POST['nombre']);
    $lugar   = $_POST['lugar'];
    $mesa    = $_POST['mesa'];
    $tel     = $_POST['telefono'];
    
    // Capturamos el ID del usuario desde la sesión
    $registrado_por = $_SESSION['usuario_id']; 

    // Si eligen capitán (referido), se guarda su ID, si no, NULL
    $capitan = !empty($_POST['capitan_id']) ? $_POST['capitan_id'] : null;

    // 2. Agregamos 'registrado_por' a la lista de columnas y un '?' más a los VALUES
    $stmt = $conn->prepare("INSERT INTO simpatizantes (cedula, nombre, lugar_votacion, mesa, telefono, capitan_id, registrado_por) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // 3. Ajustamos los tipos en bind_param ("sssiisi" -> agregamos una 'i' al final para el ID del usuario)
    $stmt->bind_param("sssiisi", $cedula, $nombre, $lugar, $mesa, $tel, $capitan, $registrado_por);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>