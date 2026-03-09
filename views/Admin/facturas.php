<?php
session_start();
require_once "../../config/db.php";

$conn = Database::Conectar();

$sql = "SELECT * FROM facturas ORDER BY id_facturas DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Facturas</title>
<link rel="stylesheet" href="../../public/css/facturas.css">
</head>

<body>

<div class="container">

<h1>Facturación</h1>

<a href="crear_factura.php" class="btn">+ Nueva Factura</a>

<table>

<tr>
<th>ID</th>
<th>venta</th>
<th>Estado</th>
<th>Método pago</th>
<th>Detalle Factura</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
<td><?php echo $row["id_facturas"]; ?></td>
<td><?php echo $row["id_venta"]?></td>
<td><?php echo $row["metodo_pago"]; ?></td>
<td><?php echo $row["estado"]; ?></td>
<td><?php echo $row["id_detallefactura"]; ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>
