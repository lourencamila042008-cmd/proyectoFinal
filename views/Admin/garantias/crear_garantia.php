<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$mensaje = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_facturas   = intval($_POST['id_facturas']);
    $id_producto   = intval($_POST['id_producto']);
    $motivo        = $_POST['motivo'];
    $solucion      = $_POST['solucion'];
    $estado        = $_POST['estado'];
    $fecha_inicio  = $_POST['fecha_inicio'];
    $fecha_fin     = $_POST['fecha_fin'];

    if (empty($fecha_inicio) || empty($fecha_fin)) {
        $mensaje = "Las fechas son obligatorias.";
    } else {
        $stmt = $conn->prepare("INSERT INTO garantias 
            (id_facturas, id_producto, motivo, solucion, estado, fecha_inicio, fecha_fin) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $id_facturas, $id_producto, $motivo, $solucion, $estado, $fecha_inicio, $fecha_fin);

        if ($stmt->execute()) {
            $stmt->close();
           echo "<script>alert('Garantía registrada correctamente'); window.location='iniciogarantias.php';</script>";
            exit;
        } else {
            $mensaje = "Error al guardar: " . $conn->error;
            $stmt->close();
        }
    }
}

// Cargar facturas y productos para los selects
$facturas  = $conn->query("SELECT f.id_facturas, c.nombre FROM facturas f JOIN clientes c ON f.id_clientes = c.id_clientes ORDER BY f.id_facturas DESC");
$productos = $conn->query("SELECT * FROM productos ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Garantía</title>
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
    position: relative;
}

body::before, body::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    filter: blur(120px);
}
body::before { width: 500px; height: 500px; top: -100px; left: -100px; }
body::after  { width: 400px; height: 400px; bottom: -100px; right: -100px; }

.container {
    background: white;
    padding: 35px;
    border-radius: 15px;
    max-width: 700px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}

h1 { text-align: center; color: #0f4c81; margin-bottom: 20px; }

form { display: flex; flex-direction: column; gap: 15px; }

label { font-weight: bold; color: #333; }

input, select {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

input:focus, select:focus {
    border-color: #3fa9f5;
    outline: none;
    box-shadow: 0 0 6px rgba(63,169,245,0.5);
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

button {
    background: #0f4c81;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
}

button:hover { background: #09365c; }

.mensaje {
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    background: #f8d7da;
    color: #721c24;
}

.volver {
    display: block;
    margin-top: 15px;
    text-align: center;
    text-decoration: none;
    color: #0f4c81;
    font-weight: bold;
}

.volver:hover { text-decoration: underline; }
</style>
</head>

<body>
<div class="container">

    <h1>Nueva Garantía</h1>

    <?php if ($mensaje != ""): ?>
        <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" action="crear_garantia.php">

        <label>Factura asociada</label>
        <select name="id_facturas" required>
            <option disabled selected>Seleccionar factura</option>
            <?php while($f = $facturas->fetch_assoc()){ ?>
                <option value="<?= $f['id_facturas'] ?>">
                    Factura #<?= $f['id_facturas'] ?> — <?= htmlspecialchars($f['nombre']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Producto</label>
        <select name="id_producto" required>
            <option disabled selected>Seleccionar producto</option>
            <?php while($p = $productos->fetch_assoc()){ ?>
                <option value="<?= $p['id_productos'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Motivo</label>
        <select name="motivo" required>
            <option disabled selected>Seleccionar motivo</option>
            <option value="ninguno">Ninguno</option>
            <option value="daño">Daño</option>
        </select>

        <label>Solución</label>
        <select name="solucion" required>
            <option disabled selected>Seleccionar solución</option>
            <option value="cambio">Cambio</option>
            <option value="reparacion">Reparación</option>
            <option value="devolucion">Devolución</option>
        </select>

        <label>Estado</label>
        <select name="estado" required>
            <option disabled selected>Seleccionar estado</option>
            <option value="pendiente">Pendiente</option>
            <option value="en_revision">En revisión</option>
            <option value="resuelto">Resuelto</option>
        </select>

        <div class="grid">
            <div>
                <label>Fecha inicio</label>
                <input type="date" name="fecha_inicio" required>
            </div>
            <div>
                <label>Fecha fin</label>
                <input type="date" name="fecha_fin" required>
            </div>
        </div>

        <button type="submit">Registrar Garantía</button>

    </form>

    <a class="volver" href="iniciogarantias.php">⬅ Volver</a>

</div>
</body>
</html>