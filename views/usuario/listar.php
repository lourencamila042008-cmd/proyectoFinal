<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 40px;
        }

        .contenedor{
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 900px;
            margin: auto;
        }

        h1{
            text-align: center;
            color: #333;
        }

        .btn-crear{
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .btn-crear:hover{
            background: #5563d6;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead{
            background: #667eea;
            color: white;
        }

        th, td{
            padding: 12px;
            text-align: center;
        }

        tbody tr{
            border-bottom: 1px solid #ddd;
            transition: 0.2s;
        }

        tbody tr:hover{
            background: #f5f7ff;
        }

        .btn-editar{
            background: #28a745;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-eliminar{
            background: #dc3545;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-editar:hover{ background: #218838; }
        .btn-eliminar:hover{ background: #c82333; }

    </style>
</head>

<body>

<div class="contenedor">
    <h1>Lista de Usuarios</h1>

    <a class="btn-crear" href="principal.php?action=crear">➕ Crear usuario</a>

    <table>
        <thead>
            <tr>
                <th>Negocio</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Contraseña</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($datos as $dato): ?>
            <tr>
                <td><?= $dato['nombre_negocio']; ?></td>
                <td><?= $dato['nombre_usuario']; ?></td>
                <td><?= $dato['apellido_usuario']; ?></td>
                <td><?= $dato['telefono']; ?></td>
                <td><?= $dato['correo']; ?></td>
                <td><?= $dato['contraseña']; ?></td>
                <td>
                    <a class="btn-editar" href="principal.php?action=editar&id=<?= $dato['id_usuario']?>">Editar</a>
                </td>
                <td>
                    <a class="btn-eliminar" href="principal.php?action=eliminar&id=<?= $dato['id_usuario']?>">Eliminar</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>

</body>
</html>
