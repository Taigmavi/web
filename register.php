<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/css/uikit.min.css" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="uk-container uk-padding">
    <h2>Registro</h2>
    <form action="register_process.php" method="POST">
        <div class="uk-margin">
            <input class="uk-input" type="text" name="usuario" placeholder="Usuario" required>
        </div>
        <div class="uk-margin">
            <input class="uk-input" type="password" name="contrasena" placeholder="Contraseña" required>
        </div>
        <div class="uk-margin">
            <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico" required>
        </div>
        <button class="uk-button uk-button-primary" type="submit">Registrarse</button>
    </form>
    <p>Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
