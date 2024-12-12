<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener la lista de proveedores
$query = $conn->query("SELECT * FROM proveedores");
$proveedores = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Gestión de Proveedores</h1>
        <div class="text-end mb-3">
            <a href="agregar_proveedor.php" class="btn btn-success">Agregar Proveedor</a>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proveedores as $proveedor): ?>
                <tr>
                    <td><?= htmlspecialchars($proveedor['id']) ?></td>
                    <td><?= htmlspecialchars($proveedor['nombre']) ?></td>
                    <td><?= htmlspecialchars($proveedor['ruc']) ?></td>
                    <td><?= htmlspecialchars($proveedor['telefono']) ?></td>
                    <td><?= htmlspecialchars($proveedor['direccion']) ?></td>
                    <td>
                        <?= htmlspecialchars($proveedor['distrito']) ?>, 
                        <?= htmlspecialchars($proveedor['provincia']) ?>, 
                        <?= htmlspecialchars($proveedor['departamento']) ?>
                    </td>
                    <td>
                        <a href="editar_proveedor.php?id=<?= $proveedor['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_proveedor.php?id=<?= $proveedor['id'] ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('¿Está seguro de eliminar este proveedor?');">Eliminar</a>
                        <a href="reporte_entradas.php?proveedor_id=<?= $proveedor['id'] ?>" class="btn btn-info btn-sm">Reporte de Entradas</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
