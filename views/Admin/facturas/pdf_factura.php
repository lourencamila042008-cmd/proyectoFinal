<?php
require '../../../vendor/autoload.php';
use Dompdf\Dompdf;

$conn = Database::Conectar();
$id = $_GET["id"];

$f = $conn->query("SELECT * FROM facturas WHERE id_facturas=$id")->fetch_assoc();

$html = "
<h1>Factura #$id</h1>
<p>Cliente: {$f['id_clientes']}</p>
<p>Estado: {$f['estado']}</p>
<p>Fecha: {$f['fecha']}</p>
";

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->render();
$pdf->stream("factura.pdf");