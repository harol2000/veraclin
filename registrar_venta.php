<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Obtener lista de clientes y productos
$clientes = $conn->query("SELECT * FROM clientes")->fetchAll(PDO::FETCH_ASSOC);
$productos = $conn->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $productos_seleccionados = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    $total = 0;
    $venta_detalle = [];

    foreach ($productos_seleccionados as $index => $producto_id) {
        $cantidad = $cantidades[$index];
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto || $producto['stock'] < $cantidad) {
            die("Error: Stock insuficiente para el producto " . htmlspecialchars($producto['nombre']));
        }

        $subtotal = $producto['precio_venta'] * $cantidad;
        $total += $subtotal;
        $venta_detalle[] = [
            'producto_id' => $producto_id,
            'cantidad' => $cantidad,
            'subtotal' => $subtotal
        ];
    }

    // Registrar la venta
    $user_id = $_SESSION['user_id']; // Obtenemos el ID del vendedor desde la sesión
    $stmt = $conn->prepare("INSERT INTO ventas (cliente_id, total, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$cliente_id, $total, $user_id]);
    
    $venta_id = $conn->lastInsertId();

    // Registrar los detalles de la venta en `salidas`
    foreach ($venta_detalle as $detalle) {
        $stmt = $conn->prepare("INSERT INTO salidas (venta_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->execute([$venta_id, $detalle['producto_id'], $detalle['cantidad'], $detalle['subtotal']]);

        // Actualizar el stock del producto
        $stmt = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$detalle['cantidad'], $detalle['producto_id']]);

        // Registrar salida en el Kardex
        $stmt = $conn->prepare("
            INSERT INTO kardex 
            (producto_id, fecha, tipo_documento, numero_documento, ingresos, salidas, saldo) 
            VALUES (?, NOW(), 'salida', ?, 0, ?, (SELECT stock FROM productos WHERE id = ?))
        ");
        $numero_documento = "BOLETA-" . $venta_id; // Generar número único para la boleta
        $stmt->execute([
            $detalle['producto_id'],
            $numero_documento,
            $detalle['cantidad'],
            $detalle['producto_id']
        ]);
    }

    header("Location: ventas.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .add-product-btn {
            margin-top: 10px;
        }
        .register-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="form-container">
            <div class="form-header text-center">Registrar Venta</div>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="cliente_id" class="form-label">Cliente:</label>
                    <select name="cliente_id" id="cliente_id" class="form-select" required>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>"><?= $cliente['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="productos-container">
                    <div class="mb-3 row align-items-center">
                        <div class="col-md-6">
                            <label for="productos[]" class="form-label">Producto:</label>
                            <select name="productos[]" class="form-select" required>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['id'] ?>">
                                        <?= $producto['nombre'] ?> (Stock: <?= $producto['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cantidades[]" class="form-label">Cantidad:</label>
                            <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100 add-product-btn" onclick="agregarProducto()">+</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 register-btn">Registrar Venta</button>
            </form>
        </div>
    </div>

    <script>
        function agregarProducto() {
            const container = document.getElementById('productos-container');
            const div = document.createElement('div');
            div.classList.add('mb-3', 'row', 'align-items-center');
            div.innerHTML = `
                <div class="col-md-6">
                    <label for="productos[]" class="form-label">Producto:</label>
                    <select name="productos[]" class="form-select" required>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= $producto['id'] ?>">
                                <?= $producto['nombre'] ?> (Stock: <?= $producto['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="cantidades[]" class="form-label">Cantidad:</label>
                    <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100 add-product-btn" onclick="this.parentNode.parentNode.remove()">-</button>
                </div>
            `;
            container.appendChild(div);
        }
    </script>
</body>
</html>

