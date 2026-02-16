<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="index.php?action=crear">crear usuari</a>
    <table>
        <thead>
            <th>nombre_negocio</th>
            <th>nombre_usuario</th>
            <th>apellido_usuario</th>
            <th>telefono</th>
            <th>correo</th>
        </thead>
        <tbody>
            <?php
            foreach ($datos as $dato):
            ?>
            <tr>
                <td><?= $dato['nombre_negocio']; ?></td>
                <td><?= $dato['nombre_usuario']; ?></td>
                <td><?= $dato['apellido_usuario']; ?></td>
                <td><?= $dato['telefono']; ?></td>
                <td><?= $dato['correo']; ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    
</body>
</html>