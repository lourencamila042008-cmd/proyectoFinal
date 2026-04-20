<?php
require_once "../../../config/db.php";
$conn =  Database::conectar();
$clientes = $conn->query("SELECT * FROM clientes ORDER BY id_clientes DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../public/css/clientes/clientes.css">
</head>
<body>

</html>
<div class="container">

<div class="topbar">
    <h1>Clientes</h1>
    <a class="btn" href="crear_cliente.php">+ Nuevo Cliente</a>
</div>

<table>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Teléfono</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php while($c = $clientes->fetch_assoc()){ ?>
<tr>

<td><?= $c["id_clientes"] ?></td>
<td><?= $c["nombre"] ?></td>
<td><?= $c["telefono"] ?></td>

<td class="acciones">

<a class= "btn-editar" href="editar_cliente.php?id=<?= $c['id_clientes'] ?>">Editar</a>

<a class="btn-eliminar" href="eliminar_cliente.php?id=<?= $c['id_clientes'] ?>" 
   onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">
   Eliminar
</a>

</td>

</tr>
<?php } ?>

</tbody>
</table>

</div>
</html>