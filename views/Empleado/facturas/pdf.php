<?php
require_once "../../../config/db.php";
require_once "../../../vendor/autoload.php";

use Dompdf\Dompdf;

$conn = Database::Conectar();

$id = $_GET['id'] ?? 0;

// 🔎 CONSULTA
$sql = "SELECT f.id_facturas, f.estado, f.fecha,
               c.nombre AS cliente,
               c.cedula,
               c.telefono,
               p.nombre AS producto,
               d.cantidad, d.precio, d.subtotal
        FROM facturas f
        JOIN clientes c ON f.id_clientes = c.id_clientes
        JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
        JOIN productos p ON d.id_productos = p.id_productos
        WHERE f.id_facturas = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if(!$data){
    die("Factura no encontrada");
}

$total = $data['subtotal'];

// 🧾 HTML DEL PDF
$html = '
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #333;
}

.header {
    display: flex;
    justify-content: space-between;
    border-bottom: 3px solid #0f4c81;
    padding-bottom: 10px;
}

.logo {
    width: 120px;
}

.company {
    text-align: right;
}

.company h2 {
    margin: 0;
    color: #0f4c81;
}

.info-box {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
}

.box {
    width: 48%;
    background: #f4f6f9;
    padding: 10px;
    border-radius: 8px;
}

.box strong {
    display: block;
    margin-bottom: 5px;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th {
    background: #0f4c81;
    color: white;
    padding: 10px;
}

.table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

.total {
    margin-top: 20px;
    text-align: right;
    font-size: 16px;
    font-weight: bold;
}

.footer {
    margin-top: 30px;
    text-align: center;
    font-size: 10px;
    color: gray;
}
</style>

<div class="header">
    <img src="public/img/logo.png" class="logo">

    <div class="company">
        <h2>INVOICEPRO S.A.S</h2>
        <p>NIT: 900.123.456-7</p>
        <p>Bogotá, Colombia</p>
        <p>Tel: 300 123 4567</p>
    </div>
</div>

<div class="info-box">

    <div class="box">
        <strong>Cliente</strong>
        '.$data['cliente'].'<br>
        CC: '.$data['cedula'].'<br>
        Tel: '.$data['telefono'].'
    </div>

    <div class="box">
        <strong>Factura</strong>
        No: '.$data['id_facturas'].'<br>
        Fecha: '.$data['fecha'].'<br>
        Estado: '.strtoupper($data['estado']).'
    </div>

</div>

<table class="table">
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
<td>'.$data['producto'].'</td>
<td>'.$data['cantidad'].'</td>
<td>$'.number_format($data['precio'],0,',','.').'</td>
<td>$'.number_format($data['subtotal'],0,',','.').'</td>
</tr>
</tbody>
</table>

<div class="total">
TOTAL: $'.number_format($total,0,',','.').'
</div>

<div class="footer">
Factura generada por InvoicePro<br>
Gracias por su compra 💙
</div>
';

// 📄 GENERAR PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();

$dompdf->stream("Factura_".$id.".pdf", ["Attachment" => false]);