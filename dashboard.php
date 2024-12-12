<?php
session_start();
include 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Incluir el menú de navegación
include 'navbar.php';

// Obtener estadísticas
$total_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_clientes = $conn->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$total_productos = $conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$total_ventas = $conn->query("SELECT COUNT(*) FROM ventas")->fetchColumn();


// Obtener datos de la empresa
$stmt = $conn->query("SELECT * FROM datos_empresa LIMIT 1");
$empresa = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener información del administrador
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_contrasena'])) {
    $contrasena_actual = $_POST['contrasena_actual'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar contraseña actual
    if (!password_verify($contrasena_actual, $usuario['password'])) {
        $mensaje_error = "La contraseña actual es incorrecta.";
    } elseif ($nueva_contrasena !== $confirmar_contrasena) {
        $mensaje_error = "Las contraseñas no coinciden.";
    } else {
        // Actualizar contraseña
        $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->execute([$nueva_contrasena_hash, $_SESSION['user_id']]);
        $mensaje_exito = "Contraseña actualizada correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .stats-container {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        margin-top: 30px;
    }
    .stats-card {
        width: 18rem;
        margin: 15px;
        text-align: center;
        transition: transform 0.3s;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center; /* Centrar contenido horizontalmente */
        justify-content: center; /* Centrar contenido verticalmente */
    }
    .stats-card:hover {
        transform: scale(1.05);
    }
    .stats-card img {
        max-width: 70px;
        max-height: 70px;
        margin-bottom: 10px;
        object-fit: contain; /* Mantener proporciones */
    }
    .stats-card .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .stats-card .card-text {
        font-size: 1.2rem;
        font-weight: 600;
    }
</style>



</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <p class="text-center">Rol: <?= ucfirst(htmlspecialchars($_SESSION['user_role'])) ?></p>

        <!-- Botones de estadísticas -->
        <div class="stats-container">
                <!-- Usuarios -->
    <a href="vendedores.php" class="text-decoration-none">
        <div class="card stats-card border-primary">
            <img src="icons/user-icon.png" alt="Usuarios">
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <p class="card-text text-primary"><?= $total_usuarios ?></p>
            </div>
        </div>
    </a>

    <!-- Clientes -->
    <a href="clientes.php" class="text-decoration-none">
        <div class="card stats-card border-success">
            <img src="icons/customer-icon.png" alt="Clientes">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="card-text text-success"><?= $total_clientes ?></p>
            </div>
        </div>
    </a>

    <!-- Productos -->
    <a href="productos.php" class="text-decoration-none">
        <div class="card stats-card border-info">
            <img src="icons/product-icon.png" alt="Productos">
            <div class="card-body">
                <h5 class="card-title">Productos</h5>
                <p class="card-text text-info"><?= $total_productos ?></p>
            </div>
        </div>
    </a>

    <!-- Ventas -->
    <a href="ventas.php" class="text-decoration-none">
        <div class="card stats-card border-warning">
            <img src="icons/sales-icon.png" alt="Ventas">
            <div class="card-body">
                <h5 class="card-title">Ventas</h5>
                <p class="card-text text-warning"><?= $total_ventas ?></p>
            </div>
        </div>
    </a>
</div>
</body>
<body>
    
    <div class="container my-5">



        
        <!-- Información de la Empresa -->
        <div class="card my-4">
            <div class="card-header">
                <h5>Datos de la Empresa</h5>
            </div>
            <div class="card-body">
                <p><strong>RUC:</strong> <?= htmlspecialchars($empresa['ruc']) ?></p>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($empresa['nombre_empresa']) ?></p>
                <p><strong>Razón Social:</strong> <?= htmlspecialchars($empresa['razon_social']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($empresa['telefono']) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($empresa['correo']) ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($empresa['direccion']) ?></p>
                <p><strong>IGV:</strong> <?= htmlspecialchars($empresa['igv']) ?>%</p>
                <?php if ($_SESSION['user_role'] === 'administrador'): ?>
                    <a href="editar_empresa.php" class="btn btn-primary">Editar Datos de la Empresa</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Información del Administrador -->
        <div class="card my-4">
            <div class="card-header">
                <h5>Información Personal</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                <p><strong>Rol:</strong> <?= ucfirst(htmlspecialchars($usuario['rol'])) ?></p>
            </div>
        </div>

        <!-- Cambio de Contraseña -->
        <div class="card my-4">
            <div class="card-header">
                <h5>Cambiar Contraseña</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($mensaje_error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($mensaje_error) ?></div>
                <?php endif; ?>
                <?php if (!empty($mensaje_exito)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($mensaje_exito) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="contrasena_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" name="contrasena_actual" id="contrasena_actual" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" required>
                    </div>
                    <button type="submit" name="cambiar_contrasena" class="btn btn-success w-100">Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
