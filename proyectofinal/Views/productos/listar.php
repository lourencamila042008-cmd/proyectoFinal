<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="principal.php?action=crear">Crear</a>
    <table>
        <thead>
            <th>categoria</th>
            <th>nombre</th>
            <th>stock</th>
            <th>precio_compra</th>
            <th>precio_venta</th>
            <th>min_stock</th>
</thead>
<tbody>
    <?php foreach($datos as $p): ?>
    <tr>
        <td><?= $p ['categoria']?></td>
        <td><?= $p ['nombre']?></td>
        
        <td><?= $p ['stock']?></td>
        <td><?= $p ['precio_compra']?></td>
        <td><?= $p ['precio_venta']?></td> 
        <td><?= $p ['min_stock']?></td> 
            <a href="principal.php?action=editar&id=<?=$p['id_producto']?>">editar</a>
            <a href="principal.php?action=eliminar&id=<?=$p['id_producto']?>">eliminar</a>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
    </table>
    
</body>
</html>