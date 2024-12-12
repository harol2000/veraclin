<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Incluir el menú de navegación
include 'navbar.php';

// Obtener los datos de los clientes
$query = $conn->query("SELECT * FROM clientes");
$clientes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center">Gestión de Clientes</h1>
        <div class="text-end mb-3">
            <a href="agregar_cliente.php" class="btn btn-success">Agregar Cliente</a>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['id']) ?></td>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td><?= htmlspecialchars($cliente['ruc']) ?></td>
                    <td><?= htmlspecialchars($cliente['correo']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                    <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este cliente?');">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
