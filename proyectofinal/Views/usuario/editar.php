<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="id" placeholder="" value="<?=$datos['id_usuario']?>"><br>
        <input type="text" name="usuario" placeholder="usuario" value="<?=$datos['usuario']?>"><br>
        <input type="text" name="contraseña" placeholder="contraseña" value="<?=$datos['contraseña']?>"><br>
        <input type="number" name="id_rol" placeholder="id_rol" value="<?=$datos['id_rol']?>"><br>
        <input type="text" name="estado" placeholder="estado" value="<?=$datos['estado']?>"><br>
        <input type="date" name="fecha_creacion" placeholder="fecha_creacion" value="<?=$datos['fecha_creacion']?>"><br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>