<?php
require_once "../../../config/db.php";
$conn = Database::conectar();
$data = $conn->query("SELECT * FROM proveedores");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Proveedores</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg,#0f4c81,#2f7bbd,#3fa9f5);
    padding:40px;
}
.container{
    background:white;
    padding:30px;
    border-radius:15px;
    max-width:900px;
    margin:auto;
}
h1{color:#0f4c81;}

.top{
    display:flex;
    justify-content:space-between;
}

.btn{
    background:#0f4c81;
    color:white;
    padding:10px;
    border-radius:8px;
    text-decoration:none;
}

table{width:100%;border-collapse:collapse;margin-top:20px;}
th{background:#0f4c81;color:white;}
th,td{padding:10px;text-align:center;}

.acciones a{
    padding:6px 10px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}

.editar{background:#28a745;}
.eliminar{background:#dc3545;}
</style>

</head>
<body>

<div class="container">

<div class="top">
<h1>Proveedores</h1>
<a href="crear_proveedor.php" class="btn">+ Nuevo</a>
</div>

<table>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Teléfono</th>
<th>Correo</th>
<th>Acciones</th>
</tr>

<?php while($p = $data->fetch_assoc()){ ?>
<tr>
<td><?= $p['id_proveedores'] ?></td>
<td><?= $p['nombre'] ?></td>
<td><?= $p['telefono'] ?></td>
<td><?= $p['correo'] ?></td>

<td class="acciones">
<a class="editar" href="editar_proveedor.php?id=<?= $p['id_proveedores'] ?>">Editar</a>

<a class="eliminar"
href="eliminar_proveedor.php?id=<?= $p['id_proveedores'] ?>"
onclick="return confirm('¿Eliminar proveedor?')">
Eliminar
</a>
</td>

</tr>
<?php } ?>

</table>

</div>

</body>
</html>