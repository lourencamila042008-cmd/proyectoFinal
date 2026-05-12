<?php
session_start();

// 🔐 SOLO ADMIN
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../Auth/login.php");
    exit();
}

require_once __DIR__ . "/../../../config/db.php";
$conn = Database::Conectar();

// 🔎 OBTENER PRODUCTOS
$sql = "SELECT * FROM productos ORDER BY id_productos DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventario - InvoicePro</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f6f9;
    padding:30px;
    color:#1e293b;
}

/* TOP */

.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:30px;
}

.top-bar h1{
    font-size:34px;
    color:#0f172a;
}

.actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.top-bar input{
    width:280px;
    padding:14px 18px;
    border:none;
    outline:none;
    border-radius:14px;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
    font-size:14px;
}

.btn{
    border:none;
    padding:14px 20px;
    border-radius:14px;
    cursor:pointer;
    font-weight:600;
    transition:.3s;
    color:white;
}

.btn-primary{
    background:#17345f;
}

.btn-primary:hover{
    background:#264c83;
}

.btn-secondary{
    background:#64748b;
}

.btn-secondary:hover{
    background:#475569;
}

/* TABLE */

.table-container{
    background:white;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    border:1px solid #e2e8f0;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f8fafc;
}

thead th{
    padding:20px;
    text-align:left;
    color:#0f172a;
    font-size:15px;
    font-weight:600;
}

tbody td{
    padding:20px;
    border-top:1px solid #e2e8f0;
    color:#475569;
    font-size:14px;
}

tbody tr{
    transition:.2s;
}

tbody tr:hover{
    background:#f8fafc;
}

/* STOCK COLORS */

.stock-ok{
    color:#16a34a;
    font-weight:600;
}

.stock-low{
    color:#dc2626;
    font-weight:600;
}

/* RESPONSIVE */

@media(max-width:900px){

    body{
        padding:20px;
    }

    .top-bar{
        flex-direction:column;
        align-items:flex-start;
    }

    .top-bar input{
        width:100%;
    }

    .actions{
        width:100%;
    }

    .btn{
        width:100%;
    }

    .table-container{
        overflow-x:auto;
    }
}

</style>
</head>

<body>

<div class="top-bar">

    <h1>Inventario</h1>

    <input type="text" id="buscar" placeholder="Buscar producto...">

    <div class="actions">

        <button class="btn btn-secondary"
        onclick="location.href='../dashboard_admin.php'">
            ⬅ Volver
        </button>

        <button class="btn btn-primary"
        onclick="location.href='agregar_producto.php'">
            ➕ Agregar producto
        </button>

    </div>

</div>

<div class="table-container">

<table id="tablaProductos">

<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Stock</th>
<th>Precio Venta</th>
<th>Precio Compra</th>
<th>Mínimo Stock</th>
</tr>
</thead>

<tbody>

<?php while($p = $result->fetch_assoc()): ?>

<tr>

<td><?= $p["id_productos"] ?></td>

<td><?= $p["nombre"] ?></td>

<td class="<?= $p["stock"] <= $p["min_stock"] ? 'stock-low' : 'stock-ok' ?>">
    <?= $p["stock"] ?>
</td>

<td>$<?= number_format($p["precio_venta"], 0, ',', '.') ?></td>

<td>$<?= number_format($p["precio_compra"], 0, ',', '.') ?></td>

<td><?= $p["min_stock"] ?></td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<script>

// 🔎 BUSCADOR EN TIEMPO REAL
document.getElementById("buscar").addEventListener("keyup", function() {

    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaProductos tbody tr");

    filas.forEach(fila => {

        let texto = fila.textContent.toLowerCase();

        fila.style.display = texto.includes(filtro)
        ? ""
        : "none";

    });

});

</script>

</body>
</html>