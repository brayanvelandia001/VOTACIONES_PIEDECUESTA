<?php
include 'conexion.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 1. Verificamos si este ID está siendo usado como capitán por otros registros
    $check_stmt = $conn->prepare("SELECT COUNT(*) as total FROM simpatizantes WHERE capitan_id = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $res = $check_stmt->get_result();
    $data = $res->fetch_assoc();

    if ($data['total'] > 0) {
        // 2. Si tiene referidos, enviamos un mensaje de advertencia
        echo "es_capitan";
    } else {
        // 3. Si no es capitán de nadie, procedemos a borrar
        $stmt = $conn->prepare("DELETE FROM simpatizantes WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
        $stmt->close();
    }
    
    $check_stmt->close();
}
$conn->close();
?>