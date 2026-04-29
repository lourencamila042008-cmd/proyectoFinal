<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$errores = [];

// PROCESAR FORMULARIO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 🔥 RECIBIR DATOS
    $id_cliente  = intval($_POST['id_clientes']);
    $id_producto = intval($_POST['id_productos']);

    $cantidad = intval($_POST['cantidad']);
    $precio   = floatval($_POST['precio']);

    $estado = trim($_POST['estado']);
    $fecha  = $_POST['fecha'];

    $subtotal = $cantidad * $precio;

    // =========================
    // VALIDACIONES
    // =========================

    if ($id_cliente <= 0) {
        $errores[] = "Cliente inválido";
    }

    if ($id_producto <= 0) {
        $errores[] = "Producto inválido";
    }

    if ($cantidad <= 0) {
        $errores[] = "Cantidad inválida";
    }

    if ($precio <= 0) {
        $errores[] = "Precio inválido";
    }

    if (empty($fecha)) {
        $errores[] = "La fecha es obligatoria";
    }

    // =========================
    // SI TODO ESTÁ BIEN
    // =========================

    if (empty($errores)) {

        // 🔥 INICIAR TRANSACCIÓN
        $conn->begin_transaction();

        try {

            // 1️⃣ INSERTAR DETALLE
            $stmt = $conn->prepare("
                INSERT INTO detallefactura
                (id_productos, cantidad, precio, subtotal)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "iidd",
                $id_producto,
                $cantidad,
                $precio,
                $subtotal
            );

            $stmt->execute();

            $id_detalle = $conn->insert_id;

            $stmt->close();

            // 2️⃣ INSERTAR FACTURA
            $stmt = $conn->prepare("
                INSERT INTO facturas
                (id_clientes, id_detallefactura, estado, fecha)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "iiss",
                $id_cliente,
                $id_detalle,
                $estado,
                $fecha
            );

            $stmt->execute();

            $id_factura = $conn->insert_id;

            $stmt->close();

            // 3️⃣ ACTUALIZAR DETALLE
            $stmt = $conn->prepare("
                UPDATE detallefactura
                SET id_facturas = ?
                WHERE id_detallefactura = ?
            ");

            $stmt->bind_param(
                "ii",
                $id_factura,
                $id_detalle
            );

            $stmt->execute();

            $stmt->close();

            // 4️⃣ RESTAR STOCK
            $stmt = $conn->prepare("
                UPDATE productos
                SET stock = stock - ?
                WHERE id_productos = ?
                AND stock >= ?
            ");

            $stmt->bind_param(
                "iii",
                $cantidad,
                $id_producto,
                $cantidad
            );

            $stmt->execute();

            if ($stmt->affected_rows == 0) {
                throw new Exception("Stock insuficiente");
            }

            $stmt->close();

            // ✅ CONFIRMAR
            $conn->commit();

            header("Location: facturas.php");
            exit;

        } catch (Exception $e) {

            // ❌ REVERSA TODO
            $conn->rollback();

            $errores[] = $e->getMessage();
        }
    }
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
    <?php if (!empty($errores)): ?>
    <div style="color:red;">
        <?php foreach($errores as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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