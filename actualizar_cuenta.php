<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Conectar a la base de datos
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION['usuario'];
    $nuevoEmail = $_POST['email'];

    // Actualizar el correo electrónico en la base de datos
    $sql = "UPDATE usuarios SET email='$nuevoEmail' WHERE usuario='$usuario'";
    if ($conn->query($sql) === TRUE) {
        // Redirigir a 'mi_cuenta.php' con un mensaje de éxito
        header('Location: mi_cuenta.php?update=success');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
