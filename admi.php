<?php
session_start();
include 'db.php';

// Verificar si el usuario es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: inicio.php'); // Redirigir a inicio.php si el usuario no es admin
    exit();
}

// Manejo de la carga de imagen
$uploadDir = 'uploads/'; // Directorio donde se guardarán las imágenes

// Inicializa la variable $buscar como una cadena vacía
$buscar = '';

// CRUD para productos
if (isset($_POST['add'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Manejo de imagen
    $imagen = $_FILES['imagen']['name'];
    $targetFile = $uploadDir . basename($imagen);
    move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile); // Guardar la imagen

    $sql = "INSERT INTO productos (nombre, precio, imagen) VALUES ('$nombre', '$precio', '$imagen')";
    $conn->query($sql);
}

// Eliminar un producto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM productos WHERE id=$id";
    $conn->query($sql);
}

// Actualizar un producto
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Verificar si se subió una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen']['name'];
        $targetFile = $uploadDir . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile);
        $sql = "UPDATE productos SET nombre='$nombre', precio='$precio', imagen='$imagen' WHERE id=$id";
    } else {
        $sql = "UPDATE productos SET nombre='$nombre', precio='$precio' WHERE id=$id";
    }
    $conn->query($sql);
}

// Búsqueda de productos
if (isset($_POST['buscar'])) {
    $buscar = $_POST['buscar'];
    $productos = $conn->query("SELECT * FROM productos WHERE nombre LIKE '%$buscar%'");
} else {
    // Leer todos los productos si no hay búsqueda
    $productos = $conn->query("SELECT * FROM productos");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Productos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/css/uikit.min.css" />
    <style>
        .uk-card {
            height: 300px; /* Ajusta la altura según sea necesario */
        }
    </style>
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
            <li><a href="lista_productos.php">Lista de Productos</a></li> <!-- Enlace a la lista de productos -->
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<div class="uk-container uk-padding">
    <h2>Administrar Productos</h2>

    <form action="admi.php" method="POST" enctype="multipart/form-data" class="uk-form-stacked">
        <div class="uk-margin">
            <label class="uk-form-label" for="nombre">Nombre del Producto</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="text" name="nombre" required>
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="precio">Precio</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="number" step="0.01" name="precio" required>
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="imagen">Imagen del Producto</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="file" name="imagen" accept="image/*" required>
            </div>
        </div>
        <button class="uk-button uk-button-primary" type="submit" name="add">Agregar Producto</button>
    </form>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
