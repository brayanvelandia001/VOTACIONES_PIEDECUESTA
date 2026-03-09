<?php
include 'conexion.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertimos a número por seguridad
    
    $stmt = $conn->prepare("DELETE FROM simpatizantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
}
$conn->close();
?>