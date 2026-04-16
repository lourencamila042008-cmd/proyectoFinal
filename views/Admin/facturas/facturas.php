<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

$sql = "SELECT * FROM facturas ORDER BY id_facturas DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Facturas</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #0f4c81, #2f7bbd, #3fa9f5);
    min-height: 100vh;
    padding: 40px;
}

/* CONTENEDOR */
.container{
    background:white;
    padding:30px;
    border-radius:15px;
    max-width:1100px;
    margin:auto;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

h1{ color:#0f4c81; }

.btn{
    background:#0f4c81;
    color:white;
    padding:10px 15px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}

/* TABLA */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#0f4c81;
    color:white;
}

th,td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f2f9ff;
}

/* ESTADOS */
.estado{
    padding:5px 10px;
    border-radius:6px;
    color:white;
    font-size:12px;
}

.pagada{background:#2ecc71;}
.pendiente{background:#f39c12;}
.anulada{background:#e74c3c;}

/* ACCIONES */
.acciones a{
    padding:6px 10px;
    border-radius:6px;
    color:white;
    text-decoration:none;
    margin:2px;
    font-size:13px;
}

.editar{background:#3498db;}
.eliminar{background:#e74c3c;}
.pdf{background:#2ecc71;}
</style>
</head>

<body>

<div class="container">

<div class="topbar">
<h1>Facturas</h1>
<a class="btn" href="crear_factura.php">+ Nueva Factura</a>
</div>

<table>
<thead>
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Estado</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php while($f = $result->fetch_assoc()){ ?>
<tr>

<td><?= $f["id_facturas"] ?></td>
<td>#<?= $f["id_clientes"] ?></td>

<td>
<span class="estado <?= $f["estado"] ?>">
<?= ucfirst($f["estado"]) ?>
</span>
</td>

<td><?= $f["fecha"] ?></td>

<td class="acciones">

<a class="editar" href="editar_factura.php?id=<?= $f["id_facturas"] ?>">✏️</a>

<a class="eliminar" 
href="eliminar_factura.php?id=<?= $f["id_facturas"] ?>"
onclick="return confirm('¿Eliminar?')">🗑️</a>

<a class="pdf" href="pdf_factura.php?id=<?= $f["id_facturas"] ?>">📄</a>

</td>

</tr>
<?php } ?>

</tbody>
</table>

</div>

</body>
</html>