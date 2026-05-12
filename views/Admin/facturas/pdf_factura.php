<?php
require_once "../../../config/db.php";
require_once "../../../vendor/autoload.php";
use Dompdf\Dompdf;

$conn = Database::Conectar();
$id = intval($_GET['id']);

// Traer factura con nombre de cliente y producto
$stmt = $conn->prepare("SELECT f.id_facturas, f.estado, f.fecha,
                               c.nombre AS nombre_cliente,
                               p.nombre AS nombre_producto,
                               d.cantidad, d.precio, d.subtotal
                        FROM facturas f
                        JOIN clientes c ON f.id_clientes = c.id_clientes
                        JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
                        JOIN productos p ON d.id_productos = p.id_productos
                        WHERE f.id_facturas = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$f = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$f) {
    die("Factura no encontrada.");
}

$html = "
<style>

body{
    font-family:Arial, sans-serif;
    padding:40px;
    color:#334155;
}

.header{
    border-bottom:3px solid #17345f;
    padding-bottom:15px;
    margin-bottom:30px;
}

h1{
    color:#17345f;
    margin:0;
}

.info{
    margin-top:20px;
}

.info p{
    margin:8px 0;
    font-size:14px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

th{
    background:#17345f;
    color:white;
    padding:14px;
    text-align:left;
    font-size:14px;
}

td{
    padding:14px;
    border-bottom:1px solid #e2e8f0;
    font-size:14px;
}

.total{
    margin-top:30px;
    text-align:right;
    font-size:20px;
    font-weight:bold;
    color:#17345f;
}

.estado{
    display:inline-block;
    padding:6px 12px;
    border-radius:20px;
    background:#dcfce7;
    color:#16a34a;
    font-size:13px;
    font-weight:bold;
}

</style>

<div class='header'>
    <h1>Factura #$id</h1>
</div>

<div class='info'>
    <p><strong>Cliente:</strong> {$f['nombre_cliente']}</p>
    <p><strong>Fecha:</strong> {$f['fecha']}</p>
    <p>
        <strong>Estado:</strong>
        <span class='estado'>
            " . ucfirst($f['estado']) . "
        </span>
    </p>
</div>

<table>

<thead>
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Subtotal</th>
</tr>
</thead>

<tbody>

<tr>
<td>{$f['nombre_producto']}</td>
<td>{$f['cantidad']}</td>
<td>$" . number_format($f['precio'], 0, ',', '.') . "</td>
<td>$" . number_format($f['subtotal'], 0, ',', '.') . "</td>
</tr>

</tbody>

</table>

<div class='total'>
Total: $" . number_format($f['subtotal'], 0, ',', '.') . "
</div>
";

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream("factura_{$id}.pdf", ['Attachment' => true]);
?>