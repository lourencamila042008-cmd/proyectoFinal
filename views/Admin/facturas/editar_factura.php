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
<link rel="stylesheet" href="../../../public/css/facturas/editar_factura.css">
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