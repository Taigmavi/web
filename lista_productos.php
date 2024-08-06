<?php
session_start();

// Verificar si el usuario está logueado y si es admin
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redirigir a inicio de sesión si no está autenticado
    exit();
}

// Conectar a la base de datos
include 'db.php';

// Inicializa la variable $buscar como una cadena vacía
$buscar = '';

// Consultar productos
if (isset($_POST['buscar'])) {
    $buscar = $_POST['buscar'];
    $productos = $conn->query("SELECT * FROM productos WHERE nombre LIKE '%$buscar%'");
} else {
    // Leer todos los productos si no hay búsqueda
    $productos = $conn->query("SELECT * FROM productos");
}

// Agregar un nuevo producto
if (isset($_POST['add'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Manejo de imagen
    $imagen = $_FILES['imagen']['name'];
    $targetFile = 'uploads/' . basename($imagen);
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
        $targetFile = 'uploads/' . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile);
        $sql = "UPDATE productos SET nombre='$nombre', precio='$precio', imagen='$imagen' WHERE id=$id";
    } else {
        $sql = "UPDATE productos SET nombre='$nombre', precio='$precio' WHERE id=$id";
    }
    $conn->query($sql);
}

// Leer todos los productos para mostrar en la tabla
$productos = $conn->query("SELECT * FROM productos");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
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
            <li><a href="?control=usuarios">Control de Usuarios</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<div class="uk-container uk-padding">
    <h2>Lista de Productos</h2>

    <form action="lista_productos.php" method="POST" class="uk-form-stacked uk-margin-top">
        <div class="uk-margin">
            <label class="uk-form-label" for="buscar">Buscar Producto por Nombre</label>
            <div class="uk-form-controls">
                <input class="uk-input" type="text" name="buscar" value="<?php echo htmlspecialchars($buscar); ?>">
            </div>
        </div>
        <button class="uk-button uk-button-default" type="submit">Buscar</button>
    </form>

    <h3>Agregar Nuevo Producto</h3>
    <form action="lista_productos.php" method="POST" enctype="multipart/form-data" class="uk-form-stacked">
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

    <h3>Lista de Productos</h3>
    <table class="uk-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $producto['id']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td><img src="uploads/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" style="width: 100px; height: auto;"></td>
                    <td>
                        <a class="uk-button uk-button-small uk-button-default" href="lista_productos.php?edit=<?php echo $producto['id']; ?>">Actualizar</a>
                        <a class="uk-button uk-button-small uk-button-danger" href="lista_productos.php?delete=<?php echo $producto['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
</body>
</html>
