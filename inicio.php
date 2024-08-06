<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php'); // Redirigir a inicio de sesión si no está autenticado
    exit();
}

// Manejar el mensaje de cierre de sesión
$alertMessage = ''; 
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $alertMessage = "Has cerrado sesión exitosamente."; // Mensaje de alerta de cierre de sesión
}

// Consultar productos de la base de datos
include 'db.php';
$productos = $conn->query("SELECT * FROM productos LIMIT 9"); // Limitar a 9 productos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/css/uikit.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* Estilos para la alerta flotante */
        .flotante-alert {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        /* Ajustes de estilo para las tarjetas */
        .uk-card {
            height: 350px; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
            transition: box-shadow 0.3s; 
        }
        .uk-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4); 
        }
        .uk-card img {
            max-height: 150px; 
            width: 100%; 
            object-fit: cover; 
        }
        /* Estilo para el icono flotante */
        .float-icon {
            position: fixed;
            bottom: 30px; 
            right: 30px; 
            z-index: 1000; 
            background-color: #007bff; 
            color: white; 
            border-radius: 50%; 
            padding: 15px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            cursor: pointer; 
        }

        .float-icon:hover {
            background-color: #0056b3; 
        }

        .uk-navbar-container{
            border-radius:2px;
            box-shadow: 0 4px 9px rgba(0, 0, 0, 0.5); 
        }
    </style>
</head>
<body>


<!-- Navegación -->
<nav class="uk-navbar uk-navbar-container" style="background-image: url('uploads/nav/nav.jpeg'); background-size: contain; background-repeat: no-repeat; background-position: center;">
    <div class="uk-navbar-left">
        <a class="uk-navbar-item uk-logo" href="#">Maili Dulces</a>
    </div>
    <div class="uk-navbar-right">
        <ul class="uk-navbar-nav">
            <li><a href="inicio.php"><i class="fa-solid fa-house"></i>Inicio</a></li> 
            <li><a href="mi_cuenta.php"><i class="fa-solid fa-user"></i>Mi Cuenta</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>


<div class="uk-container uk-padding">
    <h2>Página de Inicio</h2>

    <?php if ($alertMessage): ?>
        <div class="uk-alert-success" uk-alert>
            <p><?php echo $alertMessage; ?></p>
        </div>
    <?php endif; ?>

    <h3>Productos Disponibles</h3>
    <div class="uk-grid-column-small uk-grid-row-large uk-child-width-1-3@s uk-text-center" uk-grid>
        <?php while ($producto = $productos->fetch_assoc()): ?>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <img src="uploads/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
                    <h3><?php echo $producto['nombre']; ?></h3>
                    <p>$<?php echo number_format($producto['precio'], 2); ?></p>
                    <button class="uk-button uk-button-primary" onclick="agregarAlCarrito('<?php echo $producto['nombre']; ?>', '<?php echo $producto['precio']; ?>')">Agregar al Carrito</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Icono Flotante -->
<div class="float-icon" onclick="mostrarCarrito()">
    <i class="fas fa-shopping-cart" style="font-size: 30px; color: white;"></i>
</div>

<!-- Modal para mostrar carrito -->
<div id="carritoModal" class="uk-modal" uk-modal>
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2>Carrito de Compras</h2>
        </div>
        <div class="uk-modal-body">
            <table id="tablaCarrito" class="uk-table">
                <thead>
                    <tr>
                        <th>Nombre del Producto</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody id="carritoContenido">
                    <tr>
                        <td colspan="2">No hay productos en el carrito.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="uk-modal-footer">
            <button class="uk-button uk-button-default" onclick="vaciarCarrito()">Vaciar Carrito</button>
            <button class="uk-button uk-button-primary" onclick="hacerPedido()">Hacer Pedido</button>
            <button class="uk-button uk-button-default uk-modal-close">Cerrar</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.6.16/js/uikit.min.js"></script>
<script>
    // Variable para almacenar los productos en el carrito
    let carrito = []; // Un array para almacenar objetos de productos

    // Función para agregar productos al carrito
    function agregarAlCarrito(nombre, precio) {
        // Crear un objeto para el producto
        let producto = { nombre: nombre, precio: precio };
        // Añadir el producto al carrito
        carrito.push(producto);
        alert("Producto agregado al carrito");
    }

    // Función para vaciar el carrito
    function vaciarCarrito() {
        carrito = []; // Vaciar el array de carrito
        alert("El carrito ha sido vaciado.");
        mostrarCarrito(); // Mostrar carrito actualizado
    }

    // Función para mostrar el carrito
    function mostrarCarrito() {
        const carritoContenido = document.getElementById("carritoContenido");
        carritoContenido.innerHTML = ''; // Limpiar contenido previo

        if (carrito.length === 0) {
            carritoContenido.innerHTML = '<tr><td colspan="2">No hay productos en el carrito.</td></tr>';
        } else {
            carrito.forEach(function(producto) {
                const row = `<tr>
                    <td>${producto.nombre}</td>
                    <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                </tr>`;
                carritoContenido.innerHTML += row; // Agregar cada producto al carrito
            });
        }
        UIkit.modal("#carritoModal").show(); // Muestra el modal
    }

    // Función para hacer pedido (implementación básica)
    function hacerPedido() {
        if (carrito.length === 0) {
            alert("No hay productos en el carrito para hacer un pedido.");
        } else {
            // Aquí puedes implementar la lógica para realizar un pedido
            alert("Pedido realizado con éxito. (Funcionalidad a implementar)");
        }
    }

    // Cerrar la alerta automáticamente después de 3 segundos
    setTimeout(function() {
        const alertElement = document.querySelector('.uk-alert-success');
        if (alertElement) {
            alertElement.classList.remove('uk-animation-fade'); 
            alertElement.classList.add('uk-animation-fade-out'); 
            setTimeout(function() {
                alertElement.style.display = 'none'; 
            }, 300); 
        }
    }, 3000);
</script>
</body>
</html>
