<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: iniciogarantias.php");
    exit;
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT g.*, p.nombre AS nombre_producto, f.fecha AS fecha_factura,
           c.nombre AS nombre_cliente
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    JOIN facturas f ON g.id_facturas = f.id_facturas
    JOIN clientes c ON f.id_clientes = c.id_clientes
    WHERE g.id_garantia = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$g = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$g) {
    header("Location: iniciogarantias.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle Garantía</title>
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
    max-width: 650px;
    margin: auto;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1;
}

h1 {
    text-align: center;
    color: #0f4c81;
    margin-bottom: 25px;
}

.detalle {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.fila {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-radius: 8px;
    background: #f4f8fc;
    border-left: 4px solid #0f4c81;
}

.fila span:first-child {
    font-weight: bold;
    color: #0f4c81;
}

.fila span:last-child {
    color: #333;
}

.estado {
    padding: 5px 12px;
    border-radius: 6px;
    color: white;
    font-size: 13px;
    font-weight: bold;
}

.estado-pendiente { background: #f39c12; }
.estado-revision  { background: #3498db; }
.estado-resuelto  { background: #2ecc71; }

.acciones {
    display: flex;
    gap: 10px;
    margin-top: 25px;
    justify-content: center;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    font-size: 14px;
    color: white;
}

.btn-volver   { background: #0f4c81; }
.btn-eliminar { background: #e74c3c; }

.btn:hover { opacity: 0.85; }
</style>
</head>

<body>
<div class="container">

    <h1>Garantía #<?= $g['id_garantia'] ?></h1>

    <div class="detalle">

        <div class="fila">
            <span>Cliente</span>
            <span><?= htmlspecialchars($g['nombre_cliente']) ?></span>
        </div>

        <div class="fila">
            <span>Factura asociada</span>
            <span>#<?= $g['id_facturas'] ?> — <?= $g['fecha_factura'] ?></span>
        </div>

        <div class="fila">
            <span>Producto</span>
            <span><?= htmlspecialchars($g['nombre_producto']) ?></span>
        </div>

        <div class="fila">
            <span>Motivo</span>
            <span><?= ucfirst($g['motivo']) ?></span>
        </div>

        <div class="fila">
            <span>Solución</span>
            <span><?= ucfirst($g['solucion']) ?></span>
        </div>

        <div class="fila">
            <span>Estado</span>
            <span>
                <?php
                if($g['estado'] == 'pendiente'){
                    echo "<span class='estado estado-pendiente'>Pendiente</span>";
                } elseif($g['estado'] == 'en_revision'){
                    echo "<span class='estado estado-revision'>En revisión</span>";
                } else {
                    echo "<span class='estado estado-resuelto'>Resuelto</span>";
                }
                ?>
            </span>
        </div>

        <div class="fila">
            <span>Fecha inicio</span>
            <span><?= $g['fecha_inicio'] ?></span>
        </div>

        <div class="fila">
            <span>Fecha fin</span>
            <span><?= $g['fecha_fin'] ?></span>
        </div>

    </div>

    <div class="acciones">
        <a class="btn btn-volver" href="iniciogarantias.php">⬅ Volver</a>
        <a class="btn btn-eliminar"
           href="eliminar_garantia.php?id=<?= $g['id_garantia'] ?>"
           onclick="return confirm('¿Eliminar esta garantía?')">🗑️ Eliminar</a>
    </div>

</div>
</body>
</html>