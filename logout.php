<?php
session_start(); // Inicia la sesión
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirigir a la página de inicio de sesión con un mensaje de alerta
header('Location: index.php?logout=success'); // Usar un parámetro en la URL
exit();
?>
