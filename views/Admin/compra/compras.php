<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Compras</title>
<link rel="stylesheet" href="../../../public/css/compras/compras.css">
</head>

<body>
<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family: 'Segoe UI', sans-serif;
    background:#f4f7fb;
    padding:40px;
    color:#1e293b;
}

/* CONTENEDOR */

.container{
    max-width:1100px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

/* TOPBAR */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.topbar h1{
    font-size:30px;
    color:#0f172a;
}

.acciones-top{
    display:flex;
    gap:10px;
}

/* BOTONES */

.btn{
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    transition:.3s;
}

.btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.btn-secundario{
    background:#e2e8f0;
    color:#334155;
}

.btn-secundario:hover{
    background:#cbd5e1;
}

/* TABLA */

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#eff6ff;
}

th{
    padding:16px;
    text-align:left;
    color:#1e3a8a;
    font-size:14px;
    font-weight:700;
}

td{
    padding:16px;
    border-bottom:1px solid #e2e8f0;
    font-size:14px;
}

tbody tr:hover{
    background:#f8fafc;
}

.precio{
    color:#16a34a;
    font-weight:700;
}

/* ACCIONES */

.acciones{
    display:flex;
    gap:10px;
    align-items:center;
}

.btn-eliminar{
    background:#ef4444;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
    transition:.3s;
}

.btn-eliminar:hover{
    background:#dc2626;
}
.btn-editar{
    background:#2563eb;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
    transition:.3s;
}

.btn-editar:hover{
    background:#1d4ed8;
}

/* VACÍO */

.vacio{
    text-align:center;
    color:#64748b;
    padding:30px;
}

/* RESPONSIVE */

@media(max-width:768px){

    body{
        padding:20px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
    }

    table{
        display:block;
        overflow-x:auto;
    }

}
</style>
<div class="container">

    <div class="topbar">
        <h1>Compras</h1>

        <div class="acciones-top">
            <a href="crear_compra.php" class="btn">
                + Nueva Compra
            </a>

            <a class="btn btn-secundario" href="../dashboard_admin.php">
                ← Volver
            </a>
        </div>
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

                <td>#<?= $c['id_compra'] ?></td>

                <td>
                    <?= htmlspecialchars($c['proveedor']) ?>
                </td>

                <td class="precio">
                    $<?= number_format($c['precio_total'],0,",",".") ?>
                </td>

                <td><?= $c['fecha'] ?></td>

<td class="acciones">

    <a class="btn-editar"
    href="editar_compra.php?id=<?= $c['id_compra'] ?>">

    Editar

    </a>

    <a class="btn-eliminar"
    href="eliminar_compra.php?id=<?= $c['id_compra'] ?>"
    onclick="return confirm('¿Eliminar compra?')">

    Eliminar

    </a>

</td>
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