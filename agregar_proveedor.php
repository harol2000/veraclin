<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $ruc = trim($_POST['ruc']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $departamento = $_POST['departamento'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];

    // Validar Nombre (solo letras y espacios)
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        die("Error: El nombre del proveedor solo debe contener letras y espacios.");
    }

    // Validar RUC (exactamente 11 dígitos)
    if (!preg_match("/^\d{11}$/", $ruc)) {
        die("Error: El RUC debe contener exactamente 11 dígitos.");
    }

    // Validar Teléfono (9-15 dígitos)
    if (!preg_match("/^\d{9,15}$/", $telefono)) {
        die("Error: El teléfono debe contener entre 9 y 15 dígitos.");
    }

    // Validar Dirección (mínimo 5 caracteres)
    if (strlen($direccion) < 5) {
        die("Error: La dirección debe tener al menos 5 caracteres.");
    }

    // Validar que los campos de ubicación no estén vacíos
    if (empty($departamento) || empty($provincia) || empty($distrito)) {
        die("Error: Debe seleccionar un departamento, provincia y distrito.");
    }

    // Insertar el proveedor en la base de datos
    $stmt = $conn->prepare("
        INSERT INTO proveedores (nombre, ruc, telefono, direccion, departamento, provincia, distrito) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nombre, $ruc, $telefono, $direccion, $departamento, $provincia, $distrito]);

    header("Location: proveedores.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Proveedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Datos de departamentos, provincias y distritos
        const ubigeo = {
            "Lima": {
                "Lima": ["Miraflores", "San Isidro", "Barranco"],
                "Callao": ["Callao", "Bellavista", "La Punta"],
                "Huaral": ["Huaral", "Chancay", "Aucallama"]
            },
            "Cusco": {
                "Cusco": ["Wanchaq", "San Sebastián", "San Jerónimo"],
                "Urubamba": ["Urubamba", "Ollantaytambo", "Machu Picchu"],
                "Calca": ["Calca", "Pisac", "Lamay"]
            },
            "Arequipa": {
                "Arequipa": ["Cayma", "Yanahuara", "Cerro Colorado"],
                "Camana": ["Camana", "Ocoña", "Quilca"],
                "Islay": ["Mollendo", "Mejia", "Cocachacra"]
            }
        };

        function cargarProvincias() {
            const departamento = document.getElementById("departamento").value;
            const provincias = ubigeo[departamento];
            const provinciaSelect = document.getElementById("provincia");
            const distritoSelect = document.getElementById("distrito");

            provinciaSelect.innerHTML = "<option value=''>Seleccione una provincia</option>";
            distritoSelect.innerHTML = "<option value=''>Seleccione un distrito</option>";

            if (provincias) {
                for (const provincia in provincias) {
                    const option = document.createElement("option");
                    option.value = provincia;
                    option.textContent = provincia;
                    provinciaSelect.appendChild(option);
                }
            }
        }

        function cargarDistritos() {
            const departamento = document.getElementById("departamento").value;
            const provincia = document.getElementById("provincia").value;
            const distritos = ubigeo[departamento]?.[provincia];
            const distritoSelect = document.getElementById("distrito");

            distritoSelect.innerHTML = "<option value=''>Seleccione un distrito</option>";

            if (distritos) {
                distritos.forEach(distrito => {
                    const option = document.createElement("option");
                    option.value = distrito;
                    option.textContent = distrito;
                    distritoSelect.appendChild(option);
                });
            }
        }
    </script>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container my-5">
        <h1 class="text-center">Agregar Proveedor</h1>
        <form method="POST" action="" onsubmit="return validarFormulario(event)">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Proveedor:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="ruc" class="form-label">RUC:</label>
                <input type="text" name="ruc" id="ruc" class="form-control" maxlength="11" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="departamento" class="form-label">Departamento:</label>
                <select name="departamento" id="departamento" class="form-select" onchange="cargarProvincias()" required>
                    <option value="">Seleccione un departamento</option>
                    <option value="Lima">Lima</option>
                    <option value="Cusco">Cusco</option>
                    <option value="Arequipa">Arequipa</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="provincia" class="form-label">Provincia:</label>
                <select name="provincia" id="provincia" class="form-select" onchange="cargarDistritos()" required>
                    <option value="">Seleccione una provincia</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="distrito" class="form-label">Distrito:</label>
                <select name="distrito" id="distrito" class="form-select" required>
                    <option value="">Seleccione un distrito</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Agregar Proveedor</button>
        </form>
    </div>
</body>
</html>
