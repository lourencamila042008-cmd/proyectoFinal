<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

// CONSULTA
$sql = "SELECT * FROM compras";
$data = $conn->query($sql);

// Validar si la consulta falló
if (!$data) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Compras</title>

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
    align-items:center;
}

.btn{
    background:#0f4c81;
    color:white;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
}

.btn:hover{background:#09365c;}

table{width:100%;border-collapse:collapse;margin-top:20px;}
th{background:#0f4c81;color:white;}
th,td{padding:10px;text-align:center;}

.acciones a{
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    margin:2px;
}

.editar{background:#28a745;}
.eliminar{background:#dc3545;}
</style>

</head>
<body>

<div class="container">

<div class="top">
<h1>Compras</h1>
<a href="crear_compra.php" class="btn">+ Nueva Compra</a>
</div>

<table>
<tr>
<th>ID</th>
<th>Proveedor</th>
<th>Total</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>

<?php if($data->num_rows > 0){ ?>
    <?php while($c = $data->fetch_assoc()){ ?>
        <tr>
            <td><?= $c['id_compra'] ?></td>
            <td><?= $c['proveedor'] ?></td>
            <td>$<?= $c['precio_total'] ?></td>
            <td><?= $c['fecha'] ?></td>

            <td class="acciones">
                <a class="eliminar"
                href="eliminar_compra.php?id=<?= $c['id_compra'] ?>"
                onclick="return confirm('¿Eliminar compra?')">
                Eliminar
                </a>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="5">No hay compras registradas</td>
    </tr>
<?php } ?>

</table>

</div>

</body>
</html>