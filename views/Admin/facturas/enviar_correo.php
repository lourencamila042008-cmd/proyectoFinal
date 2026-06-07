<?php

require_once "../../../config/db.php";
require_once "../../../vendor/autoload.php";

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = Database::Conectar();

$id = intval($_GET['id']);

// Traer factura con datos del cliente y producto
$stmt = $conn->prepare("
    SELECT 
        f.id_facturas,
        f.estado,
        f.fecha,
        c.nombre AS nombre_cliente,
        c.correo,
        p.nombre AS nombre_producto,
        d.cantidad,
        d.precio,
        d.subtotal
    FROM facturas f
    JOIN clientes c ON f.id_clientes = c.id_clientes
    JOIN detallefactura d ON f.id_detallefactura = d.id_detallefactura
    JOIN productos p ON d.id_productos = p.id_productos
    WHERE f.id_facturas = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$f = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$f) {
    die("Factura no encontrada.");
}

if (empty($f['correo'])) {
    die("El cliente no tiene correo registrado.");
}

// HTML DEL PDF
$html = "
<style>

body{
    font-family: Arial, sans-serif;
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
    <h1>Factura #{$id}</h1>
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

// GENERAR PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfContent = $dompdf->output();

// ENVIAR CORREO
$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // CAMBIAR POR TU CORREO
    $mail->Username = 'lourencamila042008@gmail.com';

    // CONTRASEÑA DE APLICACIÓN DE GOOGLE
    $mail->Password = 'uiaj uvcg uxvo kjkc';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        'lourencamila042008@gmail.com',
        'InvoicePro'
    );

    $mail->addAddress(
        $f['correo'],
        $f['nombre_cliente']
    );

    $mail->isHTML(true);

    $mail->Subject = "Factura #{$id}";

    $mail->Body = "
        <h2>Hola {$f['nombre_cliente']}</h2>

        <p>Adjuntamos la factura correspondiente a su compra.</p>

        <p>
            Factura N° {$id}<br>
            Fecha: {$f['fecha']}
        </p>

        <p>Gracias por confiar en nosotros.</p>

        <br>

        <p><strong>InvoicePro</strong></p>
    ";

    $mail->addStringAttachment(
        $pdfContent,
        "Factura_{$id}.pdf",
        'base64',
        'application/pdf'
    );

    $mail->send();

    echo "
        <script>
            alert('Factura enviada correctamente al correo del cliente');
            window.location.href='facturas.php';
        </script>
    ";

} catch (Exception $e) {

    echo "
        <script>
            alert('Error al enviar el correo: " . addslashes($mail->ErrorInfo) . "');
            history.back();
        </script>
    ";
}

$conn->close();

?>