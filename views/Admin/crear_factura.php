<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../public/css/facturas.css">
</head>
<body>
<h1>Nueva Factura</h1>

<input type="text" id="buscar" placeholder="Buscar producto">

<table id="tablaProductos">

<tr>
<th>Producto</th>
<th>Precio</th>
<th>Cantidad</th>
<th>Subtotal</th>
<th></th>
</tr>

</table>

<h2>Total: $ <span id="total">0</span></h2>

<select id="metodo">
<option value="efectivo">Efectivo</option>
<option value="tarjeta">Tarjeta</option>
</select>

<button onclick="guardarFactura()">Finalizar venta</button>

<script src="../../public/js/facturacion.js"></script>
</body>
</html>