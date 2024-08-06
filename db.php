<?php
$servername = "localhost"; // Cambia si tu servidor es diferente
$username = "root"; // Cambia a tu usuario de MySQL
$password = "1234"; // Cambia a tu contraseña de MySQL
$dbname = "formulario_bd";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
