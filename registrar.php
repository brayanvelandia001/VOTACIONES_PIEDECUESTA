<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula  = $_POST['cedula'];
    $nombre  = strtoupper($_POST['nombre']);
    $lugar   = $_POST['lugar'];
    $mesa    = $_POST['mesa'];
    $tel     = $_POST['telefono'];
    // Si no eligen capitán, se guarda como NULL
    $capitan = !empty($_POST['capitan_id']) ? $_POST['capitan_id'] : null;

    $stmt = $conn->prepare("INSERT INTO simpatizantes (cedula, nombre, lugar_votacion, mesa, telefono, capitan_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiis", $cedula, $nombre, $lugar, $mesa, $tel, $capitan);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>