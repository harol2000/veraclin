<?php
include 'config.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Obtener la lista de vendedores
$query = $conn->query("SELECT * FROM usuarios WHERE rol = 'vendedor'");
$vendedores = $query->fetchAll(PDO::FETCH_ASSOC);

// Incluir el menú de navegación
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Vendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Gestión de Vendedores</h1>
        <div class="text-end mb-3">
            <a href="crear_vendedor.php" class="btn btn-success">Crear Nuevo Vendedor</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendedores as $vendedor): ?>
                <tr>
                    <td><?= $vendedor['id'] ?></td>
                    <td><?= $vendedor['nombre'] ?></td>
                    <td><?= $vendedor['email'] ?></td>
                    <td>
                        <a href="editar_vendedor.php?id=<?= $vendedor['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        
                         <a href="reporte_ventas.php?vendedor_id=<?= $vendedor['id'] ?>" class="btn btn-info btn-sm">Reporte de Ventas</a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
