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
    <link rel="stylesheet" href="../../../public/css/clientes/clientes.css">
</head>
<body>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
    color:#1e293b;
    padding:30px;
}

.container{
    max-width:1300px;
    margin:auto;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:32px;
    font-weight:700;
    color:#0f172a;
}

.btn{
    background:#1e3a5f;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:12px;
    font-size:14px;
    font-weight:600;
    transition:.3s;
}

.btn:hover{
    background:#16304d;
    transform:translateY(-2px);
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 6px 20px rgba(15,23,42,.06);
}

thead{
    background:#f8fafc;
}

th{
    padding:18px;
    text-align:left;
    font-size:13px;
    color:#64748b;
    text-transform:uppercase;
    font-weight:600;
}

td{
    padding:18px;
    border-top:1px solid #e2e8f0;
    font-size:14px;
}

tr:hover{
    background:#f8fafc;
}

.acciones{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn-editar,
.btn-eliminar{
    text-decoration:none;
    padding:10px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    transition:.3s;
}

.btn-editar{
    background:#dbeafe;
    color:#2563eb;
}

.btn-editar:hover{
    background:#2563eb;
    color:white;
}

.btn-eliminar{
    background:#fee2e2;
    color:#dc2626;
}

.btn-eliminar:hover{
    background:#dc2626;
    color:white;
}
</style>
<div class="container">

<div class="topbar">
    <h1>Clientes</h1>
    <a class="btn" href="crear_cliente.php">+ Nuevo Cliente</a>
    <a class="btn" href="../dashboard_admin.php">volver al inicio</a>
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