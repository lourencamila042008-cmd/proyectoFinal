<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$proveedores = $conn->query("SELECT * FROM proveedores");
$productos = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Compra</title>

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
    max-width:600px;
    margin:auto;
}
h1{text-align:center;color:#0f4c81;}
form{display:flex;flex-direction:column;gap:10px;}
input,select{padding:12px;border-radius:8px;border:1px solid #ccc;}
button{
    background:#0f4c81;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
}
</style>

</head>
<body>

<div class="container">
<h1>Nueva Compra</h1>

<form method="POST" action="guardar_compra.php">

<select name="id_proveedor" required>
<option value="">Proveedor</option>
<?php while($p = $proveedores->fetch_assoc()){ ?>
<option value="<?= $p['id_proveedores'] ?>"><?= $p['nombre'] ?></option>
<?php } ?>
</select>

<select name="id_producto" required>
<option value="">Producto</option>
<?php while($pr = $productos->fetch_assoc()){ ?>
<option value="<?= $pr['id_producto'] ?>"><?= $pr['nombre'] ?></option>
<?php } ?>
</select>

<input type="number" name="cantidad" placeholder="Cantidad" required>
<input type="number" name="precio" placeholder="Precio compra" required>

<button>Guardar Compra</button>

</form>
</div>

</body>
</html>