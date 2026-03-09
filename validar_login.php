<?php
include 'conexion.php';
session_start();

// Capturamos los datos del formulario
$u = $_POST['user'];
$p = $_POST['pass'];

// 1. Buscamos el usuario en la base de datos de DBeaver
$stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$res = $stmt->get_result();

if ($user = $res->fetch_assoc()) {
    // 2. Comparamos la contraseña
    // Nota: Como estamos en pruebas, usamos texto plano. 
    // Para producción usaríamos password_verify($p, $user['password'])
    if ($p == $user['password']) {
        
        // 3. CREAMOS LA SESIÓN (La llave maestra)
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre']     = $user['nombre'];
        
        // Redirigimos al Dashboard
        header("Location: index.php");
        exit(); // Detenemos la ejecución aquí

    } else {
        // Contraseña mal: Regresa al login con error
        header("Location: login.php?error=1");
        exit();
    }
} else {
    // Usuario no existe: Regresa al login con error
    header("Location: login.php?error=1");
    exit();
}
?>