<?php
require_once "../../config/db.php";

$conn = Database::Conectar();

$productos = $conn->query("SELECT * FROM productos WHERE stock > 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Nueva Factura</title>

<link rel="stylesheet" href="../../public/css/facturas.css">

</head>

<body>

<div class="container">

<h1>Nueva Factura</h1>

<form method="POST" action="../../controllers/FacturasController.php">

<select id="producto">
<option value="">Seleccionar producto</option>

<?php foreach($productos as $p): ?>

<option 
value="<?= $p['id_productos'] ?>"
data-precio="<?= $p['precio_venta'] ?>"
data-nombre="<?= $p['nombre'] ?>"
>

<?= $p['nombre'] ?> - $<?= $p['precio_venta'] ?>

</option>

<?php endforeach; ?>

</select>


<input type="number" id="cantidad" placeholder="Cantidad">


<button type="button" onclick="agregarProducto()">

Agregar producto

</button>


<table id="tabla">

<thead>

<tr>

<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>

</tr>

</thead>

<tbody></tbody>

</table>


<h2>Total: $ <span id="total">0</span></h2>

<select name="metodo_pago">

<option value="efectivo">Efectivo</option>
<option value="tarjeta">Tarjeta</option>
<option value="transferencia">Transferencia</option>

</select>


<input type="hidden" name="productos" id="productos_input">

<button type="submit" class="btn">

Finalizar venta

</button>

</form>

</div>

<script>

let total = 0
let listaProductos = []

function agregarProducto(){

let select = document.getElementById("producto")
let cantidad = document.getElementById("cantidad").value

let id = select.value
let nombre = select.options[select.selectedIndex].dataset.nombre
let precio = select.options[select.selectedIndex].dataset.precio

let subtotal = precio * cantidad

total += subtotal

document.getElementById("total").innerText = total

let tabla = document.querySelector("#tabla tbody")

tabla.innerHTML += `
<tr>

<td>${nombre}</td>
<td>$${precio}</td>
<td>${cantidad}</td>
<td>$${subtotal}</td>

</tr>
`

listaProductos.push({
id:id,
cantidad:cantidad
})

document.getElementById("productos_input").value = JSON.stringify(listaProductos)

}

</script>

</body>
</html>|1