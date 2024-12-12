<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión
header("Location: login_selector.php"); // Redirigir al selector de login
exit();
