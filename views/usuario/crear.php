<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 40px;
        }

        .contenedor{
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            margin: auto;
        }

        h1{
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        input{
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: 0.2s;
        }

        input:focus{
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 5px rgba(102,126,234,0.5);
        }

        button{
            background: #667eea;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover{
            background: #5563d6;
        }

    </style>
</head>

<body>

<div class="contenedor">
    <h1>Crear Usuario</h1>

    <form action="" method="post">
        <input type="text" name="nombre_negocio" placeholder="Nombre del negocio">
        <input type="text" name="nombre_usuario" placeholder="Nombre del usuario">
        <input type="text" name="apellido_usuario" placeholder="Apellido del usuario">
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="email" name="correo" placeholder="Correo electrónico">
        <input type="text" name="id_rol" placeholder="ID del rol">
        <input type="password" name="contraseña" placeholder="contraseña">

        <button type="submit">Guardar</button>
    </form>
</div>

</body>
</html>
