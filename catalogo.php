<?php
include 'config.php';
session_start();

// Verificar si el cliente está autenticado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

// Consulta para obtener productos
$query = $conn->query("SELECT * FROM productos");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar o Encabezado -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Catálogo</a>
            <div class="d-flex">
                <span class="navbar-text text-light me-3">
                    Bienvenido, <?= htmlspecialchars($_SESSION['cliente_nombre']) ?>
                </span>
                <a href="logout_cliente.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido del Catálogo -->
    <div class="container my-5">
        <h1 class="text-center">Catálogo de Productos</h1>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
                            <p class="card-text"><strong>Stock:</strong> <?= $producto['stock'] ?></p>
                            <p class="card-text"><strong>Precio:</strong> S/ <?= number_format($producto['precio_venta'], 2) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
