<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$errores = [];

// PROCESAR FORMULARIO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre_usuario)) {
    die("<script>alert('El nombre no puede contener números ni caracteres especiales');history.back();</script>");
}

if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido_usuario)) {
    die("<script>alert('El apellido no puede contener números ni caracteres especiales');history.back();</script>");
}
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
    <style>
        body{
    background:#f4f6f9;
    font-family:'Inter', sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.container{
    width:100%;
    max-width:650px;
    background:white;
    padding:40px;
    border-radius:28px;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    border:1px solid #e2e8f0;
}

h1{
    font-size:32px;
    color:#0f172a;
    margin-bottom:30px;
}

label{
    display:block;
    margin-bottom:8px;
    margin-top:18px;
    color:#334155;
    font-weight:500;
}

input, select{
    width:100%;
    padding:14px 16px;
    border:1px solid #dbe2ea;
    border-radius:14px;
    background:#f8fafc;
    outline:none;
    transition:.3s;
}

input:focus,
select:focus{
    border-color:#17345f;
    background:white;
}

button{
    width:100%;
    margin-top:30px;
    padding:15px;
    border:none;
    border-radius:14px;
    background:#17345f;
    color:white;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#264c83;
}

.volver{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:#64748b;
    font-weight:500;
}

hr{
    margin:30px 0;
    border:none;
    border-top:1px solid #e2e8f0;
}

h3{
    color:#17345f;
}

    </style>
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