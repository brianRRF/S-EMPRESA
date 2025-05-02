<?php
session_start();

// Eliminar las cookies
setcookie('usuario', '', time() - 3600, "/");
setcookie('nombre_usuario', '', time() - 3600, "/");
setcookie('id_cargo', '', time() - 3600, "/");

// Destruir la sesión
session_destroy();

// Redirigir al inicio de sesión
header("Location: index.html");
exit();
?>
