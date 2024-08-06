<?php
session_start();

// Verificar si el usuario está logueado y si es admin
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redirigir a inicio de sesión si no está autenticado
    exit();
}

// Conectar a la base de datos
include 'db.php';

// CRUD para usuarios
if (isset($_POST['add_user'])) {
    $usuario = $_POST['usuario'];
    $email = $_POST['email']; // Obtención del email
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Hash de la contraseña
    $rol = $_POST['rol'];

    // Crear nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, email, contrasena, rol) VALUES ('$usuario', '$email', '$contrasena', '$rol')";
    $conn->query($sql);
}

if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $sql = "DELETE FROM usuarios WHERE id=$id";
    $conn->query($sql);
}

if (isset($_GET['block_user'])) {
    $id = $_GET['block_user'];
    $sql = "UPDATE usuarios SET rol='bloqueado' WHERE id=$id"; // Cambiar el rol a bloqueado
    $conn->query($sql);
}

// Leer todos los usuarios
$usuarios = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Usuarios</title>
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
    <h2>Control de Usuarios</h2>

    <form action="control_usuarios.php" method="POST" class="uk-form-stacked uk-margin-top">
        <div class="uk-margin">
            <label class="uk-form-label" for="usuario">Nuevo Usuario</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="text" name="usuario" required placeholder="Usuario">
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="email">Email</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="email" name="email" required placeholder="Email">
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="contrasena">Contraseña</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="password" name="contrasena" required placeholder="Contraseña">
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="rol">Rol</label>
            <div class="uk-form-controls">
                <select name="rol" class="uk-select">
                    <option value="admin">Administrador</option>
                    <option value="cliente">Cliente</option>
                </select>
            </div>
        </div>
        <button class="uk-button uk-button-primary" type="submit" name="add_user">Agregar Usuario</button>
    </form>

    <table class="uk-table uk-margin-top">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['usuario']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td>
                        <a class="uk-button uk-button-small uk-button-danger" href="control_usuarios.php?delete_user=<?php echo $usuario['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                        <?php if ($usuario['rol'] !== 'admin'): ?>
                            <a class="uk-button uk-button-small uk-button-warning" href="control_usuarios.php?block_user=<?php echo $usuario['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas bloquear este usuario?');">Bloquear</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
