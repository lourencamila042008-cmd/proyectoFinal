<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="id_usuario" placeholder="id" value="<?=$datos['id_usuario']?>"><br>
        <input type="text" name="nombre_negocio" placeholder="nombre_negocio" value="<?=$datos['nombre_negocio']?>"><br>
        <input type="text" name="nombre_usuario" placeholder="nombre_usuario" value="<?=$datos['nombre_usuario']?>"><br>
        <input type="text" name="apellido_usuario" placeholder="apellido_usuario" value="<?=$datos['apellido_usuario']?>"><br>
        <input type="text" name="telefono" placeholder="telefono" value="<?=$datos['telefono']?>"><br>
        <input type="email" name="correo" placeholder="correo" value="<?=$datos['correo']?>"><br>
        <input type="text" name="id_rol" placeholder="id_rol" value="<?=$datos['id_rol']?>"><br>
         <input type="password" name="contraseña" placeholder="contraseña" value="<?=$datos['contraseña']?>"><br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>