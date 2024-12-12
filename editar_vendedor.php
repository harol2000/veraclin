<?php
include 'config.php';
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrador') {
    header("Location: login.php");
    exit();
}

// Obtener los datos del vendedor
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ? AND rol = 'vendedor'");
$stmt->execute([$id]);
$vendedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendedor) {
    echo "El vendedor no existe.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    // Actualizar vendedor
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
    $stmt->execute([$nombre, $email, $id]);

    header("Location: vendedores.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Vendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Editar Vendedor</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= $vendedor['nombre'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= $vendedor['email'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
