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
    body { font-family: Arial, sans-serif; padding: 30px; color: #333; }
    h1   { color: #0f4c81; border-bottom: 2px solid #0f4c81; padding-bottom: 10px; }
    .info { margin: 20px 0; }
    .info p { margin: 6px 0; font-size: 14px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { background: #0f4c81; color: white; padding: 10px; text-align: left; }
    td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 14px; }
    .total { text-align: right; margin-top: 20px; font-size: 16px; font-weight: bold; color: #0f4c81; }
</style>

<h1>Factura #$id</h1>

<div class='info'>
    <p><strong>Cliente:</strong> {$f['nombre_cliente']}</p>
    <p><strong>Fecha:</strong> {$f['fecha']}</p>
    <p><strong>Estado:</strong> " . ucfirst($f['estado']) . "</p>
</div>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unitario</th>
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

<div class='total'>Total: $" . number_format($f['subtotal'], 0, ',', '.') . "</div>
";

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream("factura_{$id}.pdf", ['Attachment' => true]);
?>