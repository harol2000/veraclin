<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener el ID del producto a editar
$producto_id = $_GET['id'] ?? null;
if (!$producto_id) {
    die("Producto no especificado.");
}

// Obtener los datos del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado.");
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_stock = $_POST['nuevo_stock'];
    $nuevo_nombre = trim($_POST['nombre']);

    // Validar el stock
    if ($nuevo_stock < 0) {
        echo "Error: El stock adicional debe ser mayor o igual a 0.";
        exit();
    }

    // Validar el nombre
    if (empty($nuevo_nombre)) {
        echo "Error: El nombre del producto no puede estar vacío.";
        exit();
    }

    // Actualizar el stock y el nombre en la tabla productos
    $stmt = $conn->prepare("UPDATE productos SET stock = stock + ?, nombre = ? WHERE id = ?");
    $stmt->execute([$nuevo_stock, $nuevo_nombre, $producto_id]);

    // Registrar la entrada en la tabla Kardex (solo si se actualizó el stock)
    if ($nuevo_stock > 0) {
        $stmt = $conn->prepare("
            INSERT INTO kardex 
            (producto_id, fecha, tipo_documento, numero_documento, ingresos, salidas, saldo) 
            VALUES (?, NOW(), ?, ?, ?, 0, ?)
        ");
        $numero_documento = "EDIT-STOCK-" . $producto_id; // Documento identificador único
        $stmt->execute([
            $producto_id,       // ID del producto
            'entrada',          // Tipo de documento ("entrada")
            $numero_documento,  // Número del documento
            $nuevo_stock,       // Cantidad que se incrementa
            $producto['stock'] + $nuevo_stock // Saldo actualizado
        ]);
    }

    header("Location: productos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Editar Producto</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock Actual:</label>
                    <input type="text" class="form-control" value="<?= $producto['stock'] ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="nuevo_stock" class="form-label">Agregar Stock:</label>
                    <input type="number" name="nuevo_stock" id="nuevo_stock" class="form-control" placeholder="Cantidad a agregar" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Actualizar Producto</button>
            </form>
        </div>
    </div>
</body>
</html>
