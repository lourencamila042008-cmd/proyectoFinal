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
            <th>usuario</th>
            <th>contraseña</th>
            <th>id_rol</th>
            <th>estado</th>
            <th>fecha_creacion</th>
</thead>
<tbody>
    <?php foreach($datos as $u): ?>
    <tr>
        <td><?= $u ['usuario']?></td>
        <td><?= $u ['contraseña']?></td>
        <td><?= $u ['id_rol']?></td>
        <td><?= $u ['estado']?></td>
        <td><?= $u ['fecha_creacion']?></td> 
        <td>
            <a href="principal.php?action=editar&id=<?=$u['id_usuario']?>">editar</a>
            <a href="principal.php?action=eliminar&id=<?=$u['id_usuario']?>">eliminar</a>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
    </table>
    
</body>
</html>