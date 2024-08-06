<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (usuario, contrasena, email) VALUES ('$usuario', '$contrasena', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. Puedes iniciar sesión ahora.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
