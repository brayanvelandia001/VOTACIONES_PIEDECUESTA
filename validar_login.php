<?php
include 'conexion.php';
session_start();

// Capturamos los datos del formulario
$u = $_POST['user'];
$p = $_POST['pass'];

// 1. Buscamos el usuario (Asegúrate que en la DB la columna se llame 'rol')
$stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$res = $stmt->get_result();

if ($user = $res->fetch_assoc()) {
    // 2. Comparamos la contraseña (texto plano para tus pruebas en local)
    if ($p == $user['password']) {
        
        // 3. CREAMOS LA SESIÓN CON EL ROL
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['nombre']     = $user['nombre'];
        $_SESSION['rol']        = $user['rol']; // <--- ESTO ES LO QUE NECESITA EL INDEX
        
        // Redirigimos al Dashboard
        header("Location: index.php");
        exit();

    } else {
        header("Location: login.php?error=1");
        exit();
    }
} else {
    header("Location: login.php?error=1");
    exit();
}
?>