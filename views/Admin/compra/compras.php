<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

// 🔥 CONSULTA CON JOIN
$sql = "
SELECT 
    c.id_compra,
    c.precio_total,
    c.fecha,
    p.nombre AS proveedor
FROM compras c
JOIN proveedores p 
ON c.id_proveedor = p.id_proveedores
ORDER BY c.id_compra DESC
";

$data = $conn->query($sql);

if(!$data){
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Compras</title>

<style>

body{
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg,#0f4c81,#2f7bbd,#3fa9f5);
    margin:0;
    padding:40px;
}

/* CONTENEDOR */
.container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:1000px;
    margin:auto;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

/* TOP */
.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

h1{
    color:#0f4c81;
    margin:0;
}

/* BOTÓN */
.btn{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:12px 18px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.3);
}

/* TABLA */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

th{
    background:#0f4c81;
    color:white;
    padding:15px;
}

td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f4f7fb;
}

/* ACCIONES */
.acciones a{
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    font-size:14px;
}

.eliminar{
    background:#dc3545;
}

.eliminar:hover{
    background:#b02a37;
}

/* VACÍO */
.vacio{
    padding:20px;
    color:#666;
}

</style>

</head>

<body>

<div class="container">

<div class="top">
    <h1>Compras</h1>

    <a href="crear_compra.php" class="btn">
        + Nueva Compra
    </a>
    <a class="btn" href="../dashboard_admin.php">volver al inicio</a>
</div>

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Proveedor</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>

<?php if($data->num_rows > 0){ ?>

    <?php while($c = $data->fetch_assoc()){ ?>

    <tr>

        <td><?= $c['id_compra'] ?></td>

        <td>
            <?= htmlspecialchars($c['proveedor']) ?>
        </td>

        <td>
            $<?= number_format($c['precio_total'],0,",",".") ?>
        </td>

        <td><?= $c['fecha'] ?></td>

        <td class="acciones">

            <a class="eliminar"
            href="eliminar_compra.php?id=<?= $c['id_compra'] ?>"
            onclick="return confirm('¿Eliminar compra?')">

            Eliminar

            </a>

        </td>

    </tr>

    <?php } ?>

<?php } else { ?>

<tr>
    <td colspan="5" class="vacio">
        No hay compras registradas
    </td>
</tr>

<?php } ?>

</tbody>
</table>

</div>

</body>
</html>