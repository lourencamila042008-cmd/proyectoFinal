<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lista productos</title>
</head>
<body>

<h1>Lista de productos</h1>

<!-- 🔥 BOTÓN CREAR -->
<a href="../../principal.php?controller=productos&action=crear">
    Crear Producto
</a>

<table border="1">
<thead>
<tr>
<th>Categoría</th>
<th>Nombre</th>
<th>Stock</th>
<th>Precio Compra</th>
<th>Precio Venta</th>
<th>Mínimo</th>
<th>Acción</th>
</tr>
</thead>

<tbody>
<?php if(!empty($datos)): ?>
<?php foreach ($datos as $p): ?>
<tr>
<td><?= $p['id_categoria'] ?></td>
<td><?= $p['nombre'] ?></td>
<td><?= $p['stock'] ?></td>
<td><?= $p['precio_compra'] ?></td>
<td><?= $p['precio_venta'] ?></td>
<td><?= $p['min_stock'] ?></td>

<td>
<a href="../../principal.php?controller=productos&action=eliminar&id=<?= $p['id_producto'] ?>">
Eliminar
</a>
</td>

</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>

</table>

</body>
</html>