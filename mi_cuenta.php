<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redirigir a inicio de sesión si no está autenticado
    exit();
}

// Conectar a la base de datos
include 'db.php';

// Obtener los detalles del usuario
$usuario = $_SESSION['usuario'];
$sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$result = $conn->query($sql);
$userDetails = $result->fetch_assoc(); // Obtener detalles del usuario

// Suponiendo que tengas una columna 'imagen' en tu base de datos para la foto de perfil.
$foto = isset($userDetails['imagen']) ? 'uploads/' . $userDetails['imagen'] : 'uploads/logo/default_user.png'; // Ruta de la foto de usuario

// Manejo de subida de la foto de perfil
if (isset($_POST['update_profile_pic'])) {
    $nuevaImagen = $_FILES['profile_picture']['name'];
    $targetFile = 'uploads/' . basename($nuevaImagen); // Ruta para guardar la imagen

    // Guarda la nueva imagen
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
        // Actualiza la ruta de la imagen en la base de datos
        $sql = "UPDATE usuarios SET imagen='$nuevaImagen' WHERE usuario='$usuario'";
        $conn->query($sql);
        $foto = $targetFile; // Actualiza la ruta de la foto para mostrarla en la página
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/css/uikit.min.css" />
</head>
<body>

<!-- Navegación -->
<nav class="uk-navbar uk-navbar-container" style="background-color: grey;">
    <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo" href="#">Maili Dulces</a>
    </div>
    <div class="uk-navbar-right">
        <ul class="uk-navbar-nav">
            <li><a href="inicio.php">Inicio</a></li>
            <li><a href="mi_cuenta.php">Mi Cuenta</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<div class="uk-container uk-padding">
    <h2>Detalles de Tu Cuenta</h2>

    <div class="uk-panel">
        <img class="uk-align-left uk-margin-remove-adjacent" src="<?php echo $foto; ?>" width="225" height="150" alt="Foto de Usuario"> <!-- Mostrar foto de usuario -->
        <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($userDetails['usuario']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userDetails['email']); ?></p>
    </div>

    <form action="mi_cuenta.php" method="POST" enctype="multipart/form-data" class="uk-form-stacked uk-margin-top">
        <div class="uk-margin">
            <label class="uk-form-label" for="profile_picture">Subir Foto de Perfil</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="file" name="profile_picture" accept="image/*" required>
            </div>
        </div>
        <button class="uk-button uk-button-primary" type="submit" name="update_profile_pic">Actualizar Foto de Perfil</button>
    </form>

    <form action="actualizar_cuenta.php" method="POST" class="uk-form-stacked uk-margin-top">
        <div class="uk-margin">
            <label class="uk-form-label" for="email">Actualizar Email</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="email" name="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" required>
            </div>
        </div>
        <button class="uk-button uk-button-primary" type="submit">Actualizar Datos</button>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
