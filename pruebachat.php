<?php
session_start();
include 'db.php';

// Verificar si el usuario es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: inicio.php'); // Redirigir a inicio.php si el usuario no es admin
    exit();
}

// Consulta para obtener mensajes
$mensajes = $conn->query("SELECT * FROM mensajes ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat de Administrador</title>
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
            <li><a href="admi.php">Administración</a></li>
            <li><a href="control_usuarios.php">Control de Usuarios</a></li>
            <li><a href="lista_productos.php">Lista de Productos</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<div class="uk-container uk-padding">
    <h2>Mensajes de Chat</h2>
    <div class="uk-grid-column-small uk-grid-row-large uk-child-width-1-1@s" uk-grid>
        <?php while ($msg = $mensajes->fetch_assoc()): ?>
            <div class="uk-card uk-card-default uk-card-body">
                <h4><?php echo htmlspecialchars($msg['nombre']); ?> (<?php echo $msg['fecha']; ?>):</h4>
                <p><?php echo htmlspecialchars($msg['mensaje']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
