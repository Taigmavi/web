<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contrasena, $row['contrasena'])) {
            // Establecer variables de sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $row['rol']; // Guardar el rol en la sesión

            // Redirigir a la página correspondiente
            if ($row['rol'] === 'admin') {
                header('Location: admi.php'); // Redirigir a admin
                exit();
            } else if ($row['rol'] === 'cliente') {
                header('Location: inicio.php'); // Redirigir a cliente
                exit();
            }
        } else {
            $alertMessage = "Contraseña incorrecta.";
        }
    } else {
        $alertMessage = "El usuario no existe.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/css/uikit.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
       body {
            background-color: #7a7a7a; /* Color de fondo gris completo */
            height: 100vh; /* Altura completa de la página */
            margin: 0; /* Eliminar márgenes por defecto */
            display: flex; /* Usar flexbox para centrar el contenido */
            align-items: center; /* Centrar verticalmente */
            justify-content: center; /* Centrar horizontalmente */
        }
        .login-container {
            max-width: 400px; /* Ancho máximo del formulario */
            margin: 50px auto; /* Centrar el formulario */
            padding: 20px;
            background-color: #ffffff; /* Fondo blanco del formulario */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Sombra */
        }
        .uk-input {
            margin-bottom: 15px; /* Espacio entre campos */
            padding-left: 40px; /* Espaciado a la izquierda para los iconos */
        }
        .icon-input {
            position: relative; /* Para colocar el icono */
        }
        .icon-input i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: black; /* Color negro para el icono */
        }
        .button-login {
            background-color: #007bff; /* Color del botón */
            color: white; /* Color del texto del botón */
        }
        .button-login:hover {
            background-color: #0056b3; /* Color del botón al pasar el ratón */
        }
    </style>
</head>
<body>

<div class="login-container uk-card uk-card-default uk-card-body"> <!-- Contenedor del formulario -->
    <h2 class="uk-text-center">Inicio de Sesión</h2>

    <?php if (isset($alertMessage)): ?>
        <div class="uk-alert-danger" uk-alert>
            <p><?php echo $alertMessage; ?></p>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="uk-margin icon-input">
            <i class="fas fa-user"></i> <!-- Icono de usuario -->
            <input class="uk-input" type="text" name="usuario" placeholder="Usuario" required>
        </div>

        <div class="uk-margin icon-input">
            <i class="fas fa-lock"></i> <!-- Icono de candado -->
            <input class="uk-input" type="password" name="contrasena" placeholder="Contraseña" required>
        </div>

        <button class="uk-button button-login uk-button-block" type="submit">Iniciar Sesión</button> <!-- Botón centrado -->
    </form>

    <p class="uk-text-center">No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
