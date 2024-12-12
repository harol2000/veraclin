<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Tienda de Ropa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="clientesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Clientes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="clientesDropdown">
                        <li><a class="dropdown-item" href="clientes.php">Listar Clientes</a></li>
                        <li><a class="dropdown-item" href="agregar_cliente.php">Agregar Cliente</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="proveedoresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Proveedores
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="proveedoresDropdown">
                        <li><a class="dropdown-item" href="proveedores.php">Listar Proveedores</a></li>
                        <li><a class="dropdown-item" href="agregar_proveedor.php">Agregar Proveedor</a></li>
                        <li><a class="dropdown-item" href="entradas.php">Reporte de Entradas</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Productos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="productosDropdown">
                        <li><a class="dropdown-item" href="productos.php">Listar Productos</a></li>
                        <li><a class="dropdown-item" href="agregar_producto.php">Agregar Producto</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ventasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ventas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="ventasDropdown">
                        <li><a class="dropdown-item" href="ventas.php">Reporte de Ventas</a></li>
                        <li><a class="dropdown-item" href="registrar_venta.php">Nueva Venta</a></li>
                    </ul>
                </li>
                <?php if ($_SESSION['user_role'] === 'administrador'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="vendedores.php">Vendedores</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>





</body>
</html>

<?php if ($_SESSION['user_role'] === 'administrador'): ?>
    <li class="nav-item">
        <a class="nav-link" href="vendedores.php"></a>
    </li>
<?php endif; ?>