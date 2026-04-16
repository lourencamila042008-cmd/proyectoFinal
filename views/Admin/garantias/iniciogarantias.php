<?php
require_once "../../../config/db.php";

if(isset($_GET['msg'])){ ?>

    <?php if($_GET['msg'] == "creado"){ ?>
        <div class="alert success">Garantía creada correctamente ✅</div>
    <?php } ?>

<?php }

$conn = Database::Conectar();

$garantias = $conn->query("
SELECT g.*, p.nombre
FROM garantias g
JOIN productos p ON g.id_producto = p.id_productos
ORDER BY g.id_garantia DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Garantías</title>

<link rel="stylesheet" href="../../../public/css/garantias.css">

</head>

<body>

<div class="container">

<div class="topbar">

<h1>Garantías</h1>

<a class="btn" href="crear_garantia.php">
    + Nueva garantía
</a>
</div>

<table class="table">

<thead>
<tr>

<th>ID</th>
<th>Factura</th>
<th>Fecha</th>
<th>Estado</th>
<th>Acción</th>

</tr>
</thead>

<tbody>

<?php while($g = $garantias->fetch_assoc()){ ?>

<tr>

<td><?= $g["id_garantias"] ?></td>

<td>#<?= $g["id_facturas"] ?></td>

<td><?= $g["fecha_garantia"] ?></td>

<td>

<?php
if($g["estado"] == "activa"){
echo "<span class='estado estado-activa'>Activa</span>";
}
elseif($g["estado"] == "proceso"){
echo "<span class='estado estado-proceso'>En proceso</span>";
}
else{
echo "<span class='estado estado-vencida'>Vencida</span>";
}
?>

</td>

<td>

<a class="btn-ver">Ver</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>