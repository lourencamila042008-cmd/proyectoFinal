<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$proveedores = $conn->query("SELECT * FROM proveedores");
$productos = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Nueva Compra</title>

<link rel="stylesheet" href="../../../public/css/compras/crear_compra.css">

</head>

<body>
<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI', sans-serif;
    background:#f4f7fb;
    padding:40px;
}

.container{
    background:white;
    max-width:600px;
    margin:auto;
    padding:35px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

h1{
    text-align:center;
    margin-bottom:25px;
    color:#0f172a;
}

form{
    display:flex;
    flex-direction:column;
    gap:16px;
}

input,
select{
    padding:14px;
    border:1px solid #dbe2ea;
    border-radius:10px;
    font-size:14px;
    transition:.3s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.1);
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    font-weight:600;
    transition:.3s;
}

button:hover{
    background:#1d4ed8;
}

.volver{
    display:inline-block;
    margin-top:18px;
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
}
</style>
<div class="container">

<h1>Nueva Compra</h1>

<form method="POST" action="guardar_compra.php">

<select name="id_proveedor" required>

<option value="">
Seleccionar proveedor
</option>

<?php while($p = $proveedores->fetch_assoc()){ ?>

<option value="<?= $p['id_proveedores'] ?>">

<?= htmlspecialchars($p['nombre']) ?>

</option>

<?php } ?>

</select>

<select name="id_producto" required>

<option value="">
Seleccionar producto
</option>

<?php while($pr = $productos->fetch_assoc()){ ?>

<option value="<?= $pr['id_productos'] ?>">

<?= htmlspecialchars($pr['nombre']) ?>

</option>

<?php } ?>

</select>

<input
type="number"
name="cantidad"
placeholder="Cantidad"
min="1"
required
>

<input
type="number"
name="precio"
placeholder="Precio compra"
step="0.01"
min="0"
required
>

<button type="submit">

Guardar Compra

</button>

</form>

<a class="volver" href="compras.php">

⬅ Volver

</a>

</div>

</body>
</html>