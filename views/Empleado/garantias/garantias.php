<?php
session_start();

// 🔒 ROLES
if (!isset($_SESSION["rol"]) ||
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

require_once "../../../config/db.php";
$conn = Database::Conectar();

// 🔄 ACTUALIZAR A VENCIDA
$conn->query("
    UPDATE garantias
    SET estado = 'vencida'
    WHERE fecha_fin < CURDATE()
    AND estado != 'vencida'
");

// 📊 CONTADORES
$vencidas = $conn->query("
    SELECT COUNT(*) as total
    FROM garantias
    WHERE estado = 'vencida'
")->fetch_assoc()['total'];

$porVencer = $conn->query("
    SELECT COUNT(*) as total
    FROM garantias
    WHERE fecha_fin BETWEEN CURDATE()
    AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)
    AND estado != 'vencida'
")->fetch_assoc()['total'];

// 🔎 LISTA
$garantias = $conn->query("
    SELECT g.*, p.nombre AS nombre_producto
    FROM garantias g
    JOIN productos p ON g.id_producto = p.id_productos
    ORDER BY g.id_garantia DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Garantías</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f3f4f6;
    color:#0f172a;
    padding:30px;
}

/* HEADER */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
    gap:20px;
    flex-wrap:wrap;
}

.title h1{
    font-size:45px;
    font-weight:800;
    margin-bottom:5px;
}

.title p{
    color:#64748b;
    font-size:18px;
}

/* TOP ACTIONS */

.actions{
    display:flex;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
}

.search{
    width:320px;
}

.search input{
    width:100%;
    border:none;
    outline:none;
    padding:16px 20px;
    border-radius:16px;
    background:white;
    font-size:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.btn-back{
    background:#64748b;
}

.btn-back:hover{
    background:#475569;
}

.btn{
    background:#16396b;
    color:white;
    text-decoration:none;
    padding:15px 22px;
    border-radius:14px;
    font-weight:600;
    transition:.3s;
    display:inline-block;
}

.btn:hover{
    transform:translateY(-2px);
}

/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card-stat{
    background:white;
    padding:25px;
    border-radius:24px;
    box-shadow:0 2px 10px rgba(0,0,0,0.04);
}

.card-stat h3{
    font-size:17px;
    color:#64748b;
    margin-bottom:10px;
}

.card-stat .number{
    font-size:38px;
    font-weight:700;
}

.vencidas .number{
    color:#dc2626;
}

.porvencer .number{
    color:#ea580c;
}

/* TABLA */

.table-box{
    background:white;
    border-radius:28px;
    padding:25px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    overflow-x:auto;
}

.table-title{
    font-size:24px;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    text-align:left;
    padding:18px 15px;
    font-size:14px;
    color:#64748b;
    border-bottom:2px solid #f1f5f9;
}

td{
    padding:18px 15px;
    border-bottom:1px solid #f1f5f9;
    font-size:15px;
}

tr:hover{
    background:#f8fafc;
}

/* ESTADOS */

.estado{
    padding:8px 14px;
    border-radius:12px;
    font-size:13px;
    font-weight:600;
    display:inline-block;
}

.estado-pendiente{
    background:#fef3c7;
    color:#92400e;
}

.estado-revision{
    background:#dbeafe;
    color:#1d4ed8;
}

.estado-resuelto{
    background:#dcfce7;
    color:#166534;
}

.estado-vencida{
    background:#e5e7eb;
    color:#374151;
}

tr.vencida{
    opacity:.7;
}

/* BOTON VER */

.btn-ver{
    background:#16396b;
    color:white;
    text-decoration:none;
    padding:10px 14px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    display:inline-block;
}

/* RESPONSIVE */

@media(max-width:768px){

    body{
        padding:20px;
    }

    .header{
        flex-direction:column;
        align-items:flex-start;
    }

    .search{
        width:100%;
    }

    .title h1{
        font-size:35px;
    }

    .table-box{
        padding:15px;
    }
}

</style>

</head>

<body>

<!-- HEADER -->

<div class="header">

    <div class="title">
        <h1>Garantías</h1>
        <p>Gestiona solicitudes y procesos de garantías.</p>
    </div>

    <div class="actions">

    <a class="btn btn-back" href="../dashboard_empleado.php">
        ← Volver
    </a>

    <div class="search">
        <input type="text" id="buscar"
        placeholder="Buscar garantía...">
    </div>

    <a class="btn" href="crear_garantia.php">
        + Nueva garantía
    </a>

</div>
</div>

<!-- STATS -->

<div class="stats">

    <div class="card-stat vencidas">
        <h3>Garantías vencidas</h3>
        <div class="number"><?= $vencidas ?></div>
    </div>

    <div class="card-stat porvencer">
        <h3>Por vencer (3 días)</h3>
        <div class="number"><?= $porVencer ?></div>
    </div>

</div>

<!-- TABLA -->

<div class="table-box">

    <h2 class="table-title">
        Lista de garantías
    </h2>

    <table id="tablaGarantias">

        <thead>

            <tr>
                <th>ID</th>
                <th>Factura</th>
                <th>Producto</th>
                <th>Motivo</th>
                <th>Solución</th>
                <th>Estado</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

        <?php while($g = $garantias->fetch_assoc()){ ?>

        <tr class="<?= $g['estado'] == 'vencida' ? 'vencida' : '' ?>">

            <td>#<?= $g['id_garantia'] ?></td>

            <td>#<?= $g['id_facturas'] ?></td>

            <td><?= htmlspecialchars($g['nombre_producto']) ?></td>

            <td><?= ucfirst($g['motivo']) ?></td>

            <td><?= ucfirst($g['solucion']) ?></td>

            <td>

            <?php

            if($g['estado'] == 'pendiente'){

                echo "<span class='estado estado-pendiente'>
                Pendiente
                </span>";

            } elseif($g['estado'] == 'en_revision'){

                echo "<span class='estado estado-revision'>
                En revisión
                </span>";

            } elseif($g['estado'] == 'resuelto'){

                echo "<span class='estado estado-resuelto'>
                Resuelto
                </span>";

            } else {

                echo "<span class='estado estado-vencida'>
                Vencida
                </span>";
            }

            ?>

            </td>

            <td><?= $g['fecha_inicio'] ?></td>

            <td><?= $g['fecha_fin'] ?></td>

            <td>

                <a class="btn-ver"
                href="ver_garantia.php?id=<?= $g['id_garantia'] ?>">

                    Ver

                </a>

            </td>

        </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

<script>

// 🔎 BUSCADOR

document.getElementById("buscar").addEventListener("keyup", function(){

    let filtro = this.value.toLowerCase();

    let filas = document.querySelectorAll("#tablaGarantias tbody tr");

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