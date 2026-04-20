<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subtotal = $_POST['cantidad'] * $_POST['precio'];

    // 1️⃣ Insertar detalle sin id_facturas todavía
    $stmt = $conn->prepare("INSERT INTO detallefactura (id_productos, cantidad, precio, subtotal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iidd", $_POST['id_productos'], $_POST['cantidad'], $_POST['precio'], $subtotal);
    $stmt->execute();
    $id_detalle = $conn->insert_id;
    $stmt->close();

    // 2️⃣ Insertar factura con el id_detalle recién creado
    $stmt = $conn->prepare("INSERT INTO facturas (id_clientes, id_detallefactura, estado, fecha) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $_POST['id_clientes'], $id_detalle, $_POST['estado'], $_POST['fecha']);
    $stmt->execute();
    $id_factura = $conn->insert_id;
    $stmt->close();

    // 3️⃣ Actualizar el detalle con el id_facturas
    $stmt = $conn->prepare("UPDATE detallefactura SET id_facturas = ? WHERE id_detallefactura = ?");
    $stmt->bind_param("ii", $id_factura, $id_detalle);
    $stmt->execute();
    $stmt->close();

    header("Location: facturas.php");
    exit;
}

$clientes = $conn->query("SELECT * FROM clientes");
$productos = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Factura</title>
<link rel="stylesheet" href="../../../public/css/facturas/crear_factura.css">
</head>

<body>
<div class="container">

    <h1>Nueva Factura</h1>

    <form method="POST" action="crear_factura.php">

        <label>Cliente</label>
        <select name="id_clientes" required>
            <option disabled selected>Seleccionar cliente</option>
            <?php while($c = $clientes->fetch_assoc()){ ?>
                <option value="<?= $c['id_clientes'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
            <?php } ?>
        </select>

        <label>Fecha</label>
        <input type="date" name="fecha" required>

        <label>Estado</label>
        <select name="estado">
            <option value="pagada">Pagada</option>
            <option value="pendiente">Pendiente</option>
            <option value="anulada">Anulada</option>
        </select>

        <hr>
        <h3 style="color:#0f4c81;">Producto</h3>

        <label>Producto</label>
        <select name="id_productos" required>
            <option disabled selected>Seleccionar producto</option>
            <?php while($p = $productos->fetch_assoc()){ ?>
                <option value="<?= $p['id_productos'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
            <?php } ?>
        </select>

        <label>Cantidad</label>
        <input type="number" name="cantidad" min="1" required>

        <label>Precio</label>
        <input type="number" name="precio" min="0" step="0.01" required>

        <button type="submit">Guardar Factura</button>

    </form>

    <a class="volver" href="facturas.php">⬅ Volver</a>

</div>
</body>
</html>