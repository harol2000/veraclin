<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $ruc = trim($_POST['ruc']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validar que el correo y el RUC no se repitan
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE correo = ? OR ruc = ?");
    $stmt->execute([$correo, $ruc]);
    if ($stmt->rowCount() > 0) {
        die("Error: Ya existe un cliente registrado con este correo o RUC.");
    }

    // Insertar el cliente
    $stmt = $conn->prepare("
        INSERT INTO clientes (nombre, ruc, correo, telefono, direccion, password) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nombre, $ruc, $correo, $telefono, $direccion, $password]);

    header("Location: login_cliente.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validarFormulario(event) {
            const ruc = document.getElementById("ruc").value.trim();

            // Validar RUC (exactamente 11 dígitos)
            const regexRUC = /^\d{11}$/;
            if (!regexRUC.test(ruc)) {
                alert("El RUC debe contener exactamente 11 dígitos.");
                event.preventDefault();
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Crear Cuenta</h1>
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
                <label for="correo" class="form-label">Correo Electrónico:</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
    </div>
</body>
</html>

