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
<link rel="stylesheet" href="../../../public/css/garantias/crear_garantia.css">

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