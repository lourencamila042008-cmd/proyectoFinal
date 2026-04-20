<?php
require_once "../../../config/db.php";
$conn =  Database::conectar();
$clientes = $conn->query("SELECT * FROM clientes ORDER BY id_clientes DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #1f4e79, #3a7db8);
    min-height: 100vh;
    padding: 40px;
}

/* CONTENEDOR PRINCIPAL */
.container{
    background: #eaeaea;
    padding: 30px;
    border-radius: 20px;
    max-width: 900px;
    margin: auto;
}

/* HEADER (TÍTULO + BOTÓN) */
.topbar{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

h1{
    color: #1f4e79;
    margin: 0;
}

/* BOTÓN NUEVO */
.btn{
    background: #1f4e79;
    color: white;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}

.btn:hover{
    background: #163a5a;
}

/* TABLA */
table{
    width: 100%;
    border-collapse: collapse;
}

/* ENCABEZADO */
thead{
    background: #1f4e79;
    color: white;
}

th{
    padding: 12px;
    text-align: left;
}

/* FILAS */
td{
    padding: 12px;
    background: white;
    border-bottom: 5px solid #eaeaea;
}

/* ACCIONES */
.acciones a{
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: bold;
    text-decoration: none;
    margin-right: 5px;
}

/* EDITAR */
.btn-editar{
    background: #3a7db8;
    color: white;
}

.btn-editar:hover{
    background: #2c5f91;
}

/* ELIMINAR */
.btn-eliminar{
    background: #e74c3c;
    color: white;
}

.btn-eliminar:hover{
    background: #c0392b;
}
    </style>
</body>
</html>
<div class="container">

<div class="topbar">
    <h1>Clientes</h1>
    <a class="btn" href="crear_cliente.php">+ Nuevo Cliente</a>
</div>

<table>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Teléfono</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php while($c = $clientes->fetch_assoc()){ ?>
<tr>

<td><?= $c["id_clientes"] ?></td>
<td><?= $c["nombre"] ?></td>
<td><?= $c["telefono"] ?></td>

<td class="acciones">

<a class= "btn-editar" href="editar_cliente.php?id=<?= $c['id_clientes'] ?>">Editar</a>

<a class="btn-eliminar" href="eliminar_cliente.php?id=<?= $c['id_clientes'] ?>" 
   onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">
   Eliminar
</a>

</td>

</tr>
<?php } ?>

</tbody>
</table>

</div>
</html>