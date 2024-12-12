<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se pasó un ID de proveedor
if (!isset($_GET['id'])) {
    die("Error: No se especificó un proveedor para editar.");
}

$id = $_GET['id'];

// Obtener los datos del proveedor
$query = $conn->prepare("SELECT * FROM proveedores WHERE id = ?");
$query->execute([$id]);
$proveedor = $query->fetch(PDO::FETCH_ASSOC);

if (!$proveedor) {
    die("Error: El proveedor no existe.");
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $ruc = trim($_POST['ruc']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $departamento = $_POST['departamento'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];

    // Validaciones
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        die("Error: El nombre solo debe contener letras y espacios.");
    }
    if (!preg_match("/^\d{11}$/", $ruc)) {
        die("Error: El RUC debe tener exactamente 11 dígitos.");
    }
    if (!preg_match("/^\d{9,15}$/", $telefono)) {
        die("Error: El teléfono debe tener entre 9 y 15 dígitos.");
    }
    if (strlen($direccion) < 5) {
        die("Error: La dirección debe tener al menos 5 caracteres.");
    }
    if (empty($departamento) || empty($provincia) || empty($distrito)) {
        die("Error: Debe seleccionar un departamento, provincia y distrito.");
    }

    // Actualizar los datos del proveedor
    $stmt = $conn->prepare("
        UPDATE proveedores 
        SET nombre = ?, ruc = ?, telefono = ?, direccion = ?, departamento = ?, provincia = ?, distrito = ? 
        WHERE id = ?
    ");
    $stmt->execute([$nombre, $ruc, $telefono, $direccion, $departamento, $provincia, $distrito, $id]);

    header("Location: proveedores.php?mensaje=Proveedor actualizado exitosamente.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function cargarProvincias() {
            const departamento = document.getElementById("departamento").value;
            const provinciaSelect = document.getElementById("provincia");
            provinciaSelect.innerHTML = ""; // Limpiar opciones

            if (departamento === "Lima") {
                provinciaSelect.innerHTML = `
                    <option value="Lima">Lima</option>
                    <option value="Barranca">Barranca</option>
                    <option value="Cañete">Cañete</option>
                `;
            } else if (departamento === "Cusco") {
                provinciaSelect.innerHTML = `
                    <option value="Cusco">Cusco</option>
                    <option value="Urubamba">Urubamba</option>
                    <option value="Quispicanchi">Quispicanchi</option>
                `;
            }
            cargarDistritos(); // Cargar distritos automáticamente
        }

        function cargarDistritos() {
            const provincia = document.getElementById("provincia").value;
            const distritoSelect = document.getElementById("distrito");
            distritoSelect.innerHTML = ""; // Limpiar opciones

            if (provincia === "Lima") {
                distritoSelect.innerHTML = `
                    <option value="Miraflores">Miraflores</option>
                    <option value="San Isidro">San Isidro</option>
                    <option value="Surco">Surco</option>
                `;
            } else if (provincia === "Cusco") {
                distritoSelect.innerHTML = `
                    <option value="San Jerónimo">San Jerónimo</option>
                    <option value="Wanchaq">Wanchaq</option>
                    <option value="Santiago">Santiago</option>
                `;
            }
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Editar Proveedor</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proveedor:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ruc" class="form-label">RUC:</label>
                <input type="text" name="ruc" id="ruc" class="form-control" value="<?= htmlspecialchars($proveedor['ruc']) ?>" maxlength="11" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($proveedor['telefono']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($proveedor['direccion']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="departamento" class="form-label">Departamento:</label>
                <select name="departamento" id="departamento" class="form-select" onchange="cargarProvincias()" required>
                    <option value="">Seleccione un departamento</option>
                    <option value="Lima" <?= $proveedor['departamento'] === 'Lima' ? 'selected' : '' ?>>Lima</option>
                    <option value="Cusco" <?= $proveedor['departamento'] === 'Cusco' ? 'selected' : '' ?>>Cusco</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="provincia" class="form-label">Provincia:</label>
                <select name="provincia" id="provincia" class="form-select" onchange="cargarDistritos()" required>
                    <option value="<?= htmlspecialchars($proveedor['provincia']) ?>" selected><?= htmlspecialchars($proveedor['provincia']) ?></option>
                </select>
            </div>
            <div class="mb-3">
                <label for="distrito" class="form-label">Distrito:</label>
                <select name="distrito" id="distrito" class="form-select" required>
                    <option value="<?= htmlspecialchars($proveedor['distrito']) ?>" selected><?= htmlspecialchars($proveedor['distrito']) ?></option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
        <
