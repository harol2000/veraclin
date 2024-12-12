<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ruc = $_POST['ruc'];

    // Validar Nombre (solo letras y espacios)
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        die("Error: El nombre solo debe contener letras y espacios.");
    }

    // Validar RUC (exactamente 11 dígitos)
    if (!preg_match("/^\d{11}$/", $ruc)) {
        die("Error: El RUC debe contener exactamente 11 dígitos.");
    }

    // Validar Correo (formato válido)
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: Formato de correo inválido.");
    }

    

    // Validar Teléfono (solo números, mínimo 9 dígitos)
    if (!preg_match("/^\d{9}$/", $telefono)) {
        die("Error: El teléfono debe contener entre 9 y 15 dígitos.");
    }

    // Validar Dirección (no vacía)
    if (strlen($direccion) < 5) {
        die("Error: La dirección debe tener al menos 5 caracteres.");
    }

    // Encriptar la contraseña (puedes usar password_hash para mayor seguridad)
    $contrasena = password_hash($ruc, PASSWORD_DEFAULT); // La contraseña será el RUC

    // Insertar cliente
    $stmt = $conn->prepare("
        INSERT INTO clientes (nombre, correo, telefono, direccion, ruc, password) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nombre, $correo, $telefono, $direccion, $ruc, $contrasena]);

    header("Location: clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validarFormulario(event) {
            const nombre = document.getElementById("nombre").value.trim();
            const ruc = document.getElementById("ruc").value.trim();
            const correo = document.getElementById("correo").value.trim();
            const telefono = document.getElementById("telefono").value.trim();
            const direccion = document.getElementById("direccion").value.trim();

            // Validar nombre
            const regexNombre = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            if (!regexNombre.test(nombre)) {
                alert("El nombre solo debe contener letras y espacios.");
                event.preventDefault();
                return false;
            }

            // Validar RUC
            const regexRUC = /^\d{11}$/;
            if (!regexRUC.test(ruc)) {
                alert("El RUC debe contener exactamente 11 dígitos.");
                event.preventDefault();
                return false;
            }

            // Validar correo
            const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regexCorreo.test(correo)) {
                alert("El formato del correo es inválido.");
                event.preventDefault();
                return false;
            }

            // Validar teléfono
            const regexTelefono = /^\d{9}$/;
            if (!regexTelefono.test(telefono)) {
                alert("El teléfono debe contener entre 9 y 15 dígitos.");
                event.preventDefault();
                return false;
            }

            // Validar dirección
            if (direccion.length < 5) {
                alert("La dirección debe tener al menos 5 caracteres.");
                event.preventDefault();
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Agregar Cliente</h1>
        <form method="POST" action="" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="ruc" class="form-label">RUC:</label>
                <input type="text" name="ruc" id="ruc" class="form-control" maxlength="11" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-control" maxlength="9" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar Cliente</button>
        </form>
    </div>
</body>
</html>
