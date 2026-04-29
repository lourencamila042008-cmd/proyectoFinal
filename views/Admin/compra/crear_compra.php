<?php
require_once "../../../config/db.php";
$conn = Database::conectar();

$proveedores = $conn->query("SELECT * FROM proveedores");
$productos = $conn->query("SELECT * FROM productos");

$stmt = $conn->prepare("
UPDATE productos 
SET stock = stock + ? 
WHERE id_productos = ?
");

$stmt->bind_param(
    "ii",
    $cantidad,
    $id_producto
);

$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<title>Nueva Compra</title>

<style>

body{
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg,#0f4c81,#2f7bbd,#3fa9f5);
    padding:40px;
}

/* CONTENEDOR */
.container{
    background:white;
    padding:35px;
    border-radius:18px;
    max-width:600px;
    margin:auto;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

/* TITULO */
h1{
    text-align:center;
    color:#0f4c81;
    margin-bottom:25px;
}

/* FORMULARIO */
form{
    display:flex;
    flex-direction:column;
    gap:15px;
}

/* INPUTS */
input, select{
    padding:14px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:15px;
    transition:0.3s;
}

input:focus,
select:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 8px rgba(37,99,235,0.3);
}

/* BOTÓN */
button{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:14px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    font-weight:bold;
    transition:0.3s;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.3);
}

/* VOLVER */
.volver{
    display:inline-block;
    margin-top:15px;
    color:#0f4c81;
    text-decoration:none;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h1>Nueva Compra</h1>

<form method="POST" action="guardar_compra.php">

<!-- PROVEEDOR -->
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

<!-- PRODUCTO -->
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

<!-- CANTIDAD -->
<input
type="number"
name="cantidad"
placeholder="Cantidad"
min="1"
required
>

<!-- PRECIO -->
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