<?php
session_start();
$alertMessage = ''; // Variable para almacenar el mensaje de alerta

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $alertMessage = "Has cerrado sesión exitosamente."; // Mensaje de alerta
}

// Aquí va el resto de tu código del inicio de sesión...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/css/uikit.min.css" />
</head>
<body>

<div class="uk-container uk-padding">
    <h2>Inicio de Sesión</h2>

    <?php if ($alertMessage): ?>
        <div id="alert" class="uk-alert-success uk-animation-fade" uk-alert>
            <a href="#" class="uk-alert-close" uk-close></a>
            <p><?php echo $alertMessage; ?></p>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST"> <!-- Asegúrate de apuntar a tu script de inicio de sesión -->
        <div class="uk-margin">
            <input class="uk-input" type="text" name="usuario" placeholder="Usuario" required>
        </div>
        <div class="uk-margin">
            <input class="uk-input" type="password" name="contrasena" placeholder="Contraseña" required>
        </div>
        <button class="uk-button uk-button-primary" type="submit">Iniciar Sesión</button>
    </form>
    <p>No tienes una cuenta? <a href="register.php">Registrate aquí</a></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
<script>
    // Cerrar la alerta automáticamente después de 3 segundos con animación de fade
    setTimeout(function() {
        const alertElement = document.getElementById('alert');
        if (alertElement) {
            alertElement.classList.remove('uk-animation-fade'); // Remover la animación fade para aplicar fade out
            alertElement.classList.add('uk-animation-fade-out'); // Añadir fade-out para la animación de salida
            setTimeout(function() {
                alertElement.style.display = 'none'; // Ocultar el elemento después de la animación
            }, 300); // Tiempo de la animación de salida, ajusta según sea necesario
        }
    }, 3000);
</script>
</body>
</html>
