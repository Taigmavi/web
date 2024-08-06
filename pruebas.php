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
        /* Estilos para el navbar */
        .navbar {
            background-image: url('ruta/a/tu-imagen-unida.jpg'); /* Reemplaza con la ruta de tu imagen unida */
            background-size: cover; /* Ajusta el tamaño de la imagen */
            background-position: center; /* Centra la imagen en el navbar */
            height: 80px; /* Ajusta la altura del navbar según sea necesario */
            display: flex;
            align-items: center; /* Centrar verticalmente los elementos dentro del navbar */
            box-shadow: 0 4px 9px rgba(0, 0, 0, 0.5); /* Sombra para el navbar */
            position: relative; /* Para permitir el uso de pseudo-elementos */
        }

        /* Añadir un pseudo-elemento para la transparencia de la imagen de fondo */
        .navbar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.5); /* Color blanco semi-transparente */
            z-index: 1; /* Asegúrate de que esté arriba de la imagen */
        }

        /* Asegura que el contenido del navbar esté por encima del pseudo-elemento */
        .uk-navbar-container {
            position: relative; /* Asegura que el contenido se muestre encima */
            z-index: 2; /* Muestra el contenido del navbar por encima */
        }

        /* Estilos para las tarjetas */
        .uk-card {
            height: 350px; /* Altura fija para todas las tarjetas */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra */
            transition: box-shadow 0.3s; 
        }
        .uk-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4); /* Sombra al pasar el mouse */
        }
        .uk-card img {
            max-height: 150px; /* Ajuste la imagen al tamaño de la tarjeta */
            width: 100%; 
            object-fit: cover; 
            border-top-left-radius: 15px; /* Bordes redondeados en la parte superior */
            border-top-right-radius: 15px; /* Bordes redondeados en la parte superior */
        }

        /* Estilo para el icono flotante del carrito */
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

        /* Estilo para el icono flotante de Chat */
        .chat-icon {
            position: fixed;
            bottom: 30px; 
            left: 30px; /* Colocado a la izquierda */
            z-index: 1000; 
            background-color: #25D366; /* Color de WhatsApp */
            color: white; 
            border-radius: 50%; 
            padding: 15px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            cursor: pointer; 
            display: none; /* Inicialmente oculto */
        }
        .chat-icon:hover {
            background-color: #128C7E; 
        }

        /* Estilos para el cuadro de chat */
        .chat-box {
            display: none; /* Inicialmente oculto */
            position: fixed;
            bottom: 100px; /* A 100px del fondo */
            left: 30px; /* A la misma posición que el botón */
            width: 300px; /* Ancho del cuadro de chat */
            max-height: 400px; /* Altura máxima */
            background-color: white; /* Fondo blanco */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Sombra */
            overflow: hidden; /* Evita que el contenido desborde */
            z-index: 999; /* Asegura que esté por encima de otros elementos */
        }

        /* Estilos para el área de mensajes */
        .chat-messages {
            padding: 10px; /* Espacio interno */
            height: 300px; /* Altura firme para el área de mensajes */
            overflow-y: auto; /* Desplazamiento vertical si hay desbordamiento */
        }

        /* Estilos para la entrada de texto */
        .chat-input {
            border: none; /* Sin borde */
            padding: 10px; /* Espaciado */
            width: calc(100% - 20px); /* Ancho total menos el padding */
            margin: 10px; /* Espacio */
            border-radius: 5px; /* Borde redondeado */
        }

        /* Estilos para el botón de enviar */
        .chat-send {
            background-color: #007bff; /* Color del botón */
            color: white; /* Texto en blanco */
            padding: 10px; /* Espacio interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            cursor: pointer; /* Cursor de mano */
            width: calc(100% - 20px); /* Ancho total menos el margen */
            margin: 10px; /* Espacio */
        }
    </style>
</head>
<body>

<!-- Navegación -->
<div class="navbar">
    <nav class="uk-navbar uk-navbar-container" style="background-color: transparent;">
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
</div>

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

<!-- Icono Flotante del Carrito -->
<div class="float-icon" onclick="mostrarCarrito()">
    <i class="fas fa-shopping-cart" style="font-size: 30px; color: white;"></i>
</div>

<!-- Icono Flotante de Chat -->
<div class="chat-icon" onclick="toggleChat()">
    <i class="fa-solid fa-comments" style="font-size: 30px; color: white;"></i>
</div>

<!-- Cuadro de Chat -->
<div class="chat-box" id="chatBox">
    <div class="chat-messages" id="chatMessages">
        <p>Bienvenido al chat de ayuda, ¿cómo puedo asistirte?</p>
    </div>
    <input type="text" id="chatInput" class="chat-input" placeholder="Escribe un mensaje..." />
    <button class="chat-send" onclick="sendMessage()">Enviar</button>
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
            <button class="uk-button uk-button-primary" id="hacerPedidoBtn">Hacer Pedido</button>
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

    // Función para hacer pedido y redirigir a WhatsApp
    document.getElementById("hacerPedidoBtn").onclick = function() {
        if (carrito.length === 0) {
            alert("No hay productos en el carrito para hacer un pedido.");
            return;
        }
        
        let mensaje = "Hola buenas quisiera este pedido: ";
        carrito.forEach(function(producto) {
            mensaje += `\n- ${producto.nombre} ($${parseFloat(producto.precio).toFixed(2)})`;
        });
        
        const telefono = "51998180770"; // Tu número de teléfono sin el caracter '+' o espacio
        const mensajeEncoded = encodeURIComponent(mensaje); // Codificar el mensaje para URL
        window.open(`https://wa.me/${telefono}?text=${mensajeEncoded}`, '_blank'); // Redirigir a WhatsApp
    };

    // Función para mostrar u ocultar el cuadro de chat
    function toggleChat() {
        const chatBox = document.getElementById("chatBox");
        chatBox.style.display = chatBox.style.display === "block" ? "none" : "block"; // Alternar la visibilidad
    }

    // Función para enviar un mensaje
    function sendMessage() {
        const input = document.getElementById("chatInput");
        const message = input.value.trim();
        if (message !== "") {
            const chatMessages = document.getElementById("chatMessages");
            chatMessages.innerHTML += `<p><strong>Tú:</strong> ${message}</p>`;
            input.value = ""; // Limpiar el campo de entrada
            chatMessages.scrollTop = chatMessages.scrollHeight; // Desplazarse hacia abajo
        }
    }

    // Mostrar el botón de chat de ayuda después de 5 segundos
    setTimeout(() => {
        document.querySelector('.chat-icon').style.display = 'block'; // Mostrar el botón de chat
    }, 5000);

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
