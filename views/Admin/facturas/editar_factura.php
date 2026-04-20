<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$mensaje = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: facturas.php");
    exit;
}
$id = intval($_GET['id']);

// Cargar datos actuales de la factura y su detalle
$stmt = $conn->prepare("SELECT f.*, d.id_detallefactura, d.id_productos, d.cantidad, d.precio
                        FROM facturas f
                        JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
                        WHERE f.id_facturas = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$factura = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$factura) {
    header("Location: facturas.php");
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estado   = $_POST['estado'];
    $fecha    = $_POST['fecha'];
    $cantidad = intval($_POST['cantidad']);
    $precio   = floatval($_POST['precio']);
    $subtotal = $cantidad * $precio;
    $id_productos = intval($_POST['id_productos']);

    // Actualizar factura
    $stmt = $conn->prepare("UPDATE facturas SET estado=?, fecha=? WHERE id_facturas=?");
    $stmt->bind_param("ssi", $estado, $fecha, $id);
    $stmt->execute();
    $stmt->close();

    // Actualizar detalle
    $stmt = $conn->prepare("UPDATE detallefactura SET id_productos=?, cantidad=?, precio=?, subtotal=? WHERE id_detallefactura=?");
    $stmt->bind_param("iiddi", $id_productos, $cantidad, $precio, $subtotal, $factura['id_detallefactura']);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Factura actualizada'); window.location='facturas.php';</script>";
    exit;
}

$clientes  = $conn->query("SELECT * FROM clientes");
$productos = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Factura</title>
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

    <h1>Editar Factura #<?= $id ?></h1>

    <form method="POST" action="editar_factura.php?id=<?= $id ?>">

        <label>Estado</label>
        <select name="estado">
            <option value="pagada"    <?= $factura['estado'] == 'pagada'    ? 'selected' : '' ?>>Pagada</option>
            <option value="pendiente" <?= $factura['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="anulada"   <?= $factura['estado'] == 'anulada'   ? 'selected' : '' ?>>Anulada</option>
        </select>

        <label>Fecha</label>
        <input type="date" name="fecha" value="<?= $factura['fecha'] ?>" required>

        <hr>
        <h3 style="color:#0f4c81;">Producto</h3>

        <label>Producto</label>
        <select name="id_productos">
            <?php while($p = $productos->fetch_assoc()){ ?>
                <option value="<?= $p['id_productos'] ?>"
                    <?= $p['id_productos'] == $factura['id_productos'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nombre']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Cantidad</label>
        <input type="number" name="cantidad" value="<?= $factura['cantidad'] ?>" min="1" required>

        <label>Precio</label>
        <input type="number" name="precio" value="<?= $factura['precio'] ?>" min="0" step="0.01" required>

        <button type="submit">Guardar Cambios</button>

    </form>

    <a class="volver" href="facturas.php">⬅ Volver</a>

</div>
</body>
</html>