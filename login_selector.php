<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($tipo === 'cliente') {
        // Validar cliente
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE correo = ?");
        $stmt->execute([$email]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente && password_verify($password, $cliente['password'])) {
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nombre'] = $cliente['nombre'];
            header("Location: catalogo.php");
            exit();
        } else {
            $error = "Correo o contraseña incorrectos para cliente.";
        }
    } elseif ($tipo === 'vendedor') {
        // Validar vendedor/administrador
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['rol'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Correo o contraseña incorrectos para vendedor/administrador.";
        }
    } else {
        $error = "Tipo de usuario no válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .tab-content {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center">Bienvenido</h2>
            <p class="text-center text-muted">Selecciona tu tipo de usuario para iniciar sesión.</p>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <ul class="nav nav-tabs" id="loginTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab" data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente" aria-selected="true">Cliente</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="vendedor-tab" data-bs-toggle="tab" data-bs-target="#vendedor" type="button" role="tab" aria-controls="vendedor" aria-selected="false">Vendedor/Administrador</button>
                </li>
            </ul>
            <div class="tab-content" id="loginTabsContent">
                <!-- Formulario de Cliente -->
                <div class="tab-pane fade show active" id="cliente" role="tabpanel" aria-labelledby="cliente-tab">
                    <form method="POST" action="">
                        <input type="hidden" name="tipo" value="cliente">
                        <div class="mb-3">
                            <label for="cliente-email" class="form-label">Correo:</label>
                            <input type="email" name="email" id="cliente-email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="cliente-password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" id="cliente-password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        <div class="text-center mt-3">
                            <a href="registro_cliente.php" class="btn btn-secondary btn-sm">Registrarse</a>
                        </div>
                    </form>
                </div>
                <!-- Formulario de Vendedor/Administrador -->
                <div class="tab-pane fade" id="vendedor" role="tabpanel" aria-labelledby="vendedor-tab">
                    <form method="POST" action="">
                        <input type="hidden" name="tipo" value="vendedor">
                        <div class="mb-3">
                            <label for="vendedor-email" class="form-label">Correo:</label>
                            <input type="email" name="email" id="vendedor-email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="vendedor-password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" id="vendedor-password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
