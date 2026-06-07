<?php
require_once "../../../config/db.php";
$conn = Database::Conectar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: iniciogarantias.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT g.*, 
           p.nombre AS nombre_producto,
           f.fecha AS fecha_factura,
           c.nombre AS nombre_cliente
    FROM garantias g
    JOIN productos p 
        ON g.id_producto = p.id_productos
    JOIN facturas f 
        ON g.id_facturas = f.id_facturas
    JOIN clientes c 
        ON f.id_clientes = c.id_clientes
    WHERE g.id_garantia = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$g = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$g) {
    header("Location: iniciogarantias.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detalle Garantía</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background:#f3f4f6;
    padding:30px;
    color:#0f172a;
}

/* CONTENEDOR */

.container{
    max-width:900px;
    margin:auto;
}

/* HEADER */

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.title h1{
    font-size:42px;
    font-weight:800;
    margin-bottom:5px;
}

.title p{
    color:#64748b;
    font-size:18px;
}

/* BOTONES */

.actions-top{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.btn{
    padding:14px 22px;
    border-radius:14px;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
    display:inline-block;
    color:white;
}

.btn:hover{
    transform:translateY(-2px);
}

.btn-volver{
    background:#64748b;
}

.btn-volver:hover{
    background:#475569;
}

.btn-eliminar{
    background:#dc2626;
}

.btn-eliminar:hover{
    background:#b91c1c;
}

/* CARD */

.card{
    background:white;
    border-radius:28px;
    padding:35px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

/* FILAS */

.detalle{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.fila{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 20px;
    background:#f8fafc;
    border-radius:16px;
    gap:20px;
}

.fila span:first-child{
    font-weight:700;
    color:#334155;
}

.fila span:last-child{
    color:#0f172a;
    text-align:right;
}

/* ESTADOS */

.estado{
    padding:8px 14px;
    border-radius:12px;
    font-size:13px;
    font-weight:700;
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

/* ACCIONES */

.acciones{
    margin-top:30px;
    display:flex;
    justify-content:flex-end;
    gap:15px;
    flex-wrap:wrap;
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

    .title h1{
        font-size:34px;
    }

    .card{
        padding:25px;
    }

    .fila{
        flex-direction:column;
        align-items:flex-start;
    }

    .fila span:last-child{
        text-align:left;
    }

    .acciones{
        justify-content:stretch;
    }

    .acciones a{
        width:100%;
        text-align:center;
    }
}

</style>

</head>

<body>

<div class="container">

    <!-- HEADER -->

    <div class="header">

        <div class="title">

            <h1>
                Garantía #<?= $g['id_garantia'] ?>
            </h1>

            <p>
                Información completa de la garantía.
            </p>

        </div>

        <div class="actions-top">

            <a class="btn btn-volver"
            href="garantias.php">

                ← Volver

            </a>

        </div>

    </div>

    <!-- CARD -->

    <div class="card">

        <div class="detalle">

            <!-- CLIENTE -->

            <div class="fila">

                <span>Cliente</span>

                <span>
                    <?= htmlspecialchars($g['nombre_cliente']) ?>
                </span>

            </div>

            <!-- FACTURA -->

            <div class="fila">

                <span>Factura asociada</span>

                <span>
                    #<?= $g['id_facturas'] ?>
                    — <?= $g['fecha_factura'] ?>
                </span>

            </div>

            <!-- PRODUCTO -->

            <div class="fila">

                <span>Producto</span>

                <span>
                    <?= htmlspecialchars($g['nombre_producto']) ?>
                </span>

            </div>

            <!-- MOTIVO -->

            <div class="fila">

                <span>Motivo</span>

                <span>
                    <?= ucfirst($g['motivo']) ?>
                </span>

            </div>

            <!-- SOLUCION -->

            <div class="fila">

                <span>Solución</span>

                <span>
                    <?= ucfirst($g['solucion']) ?>
                </span>

            </div>

            <!-- ESTADO -->

            <div class="fila">

                <span>Estado</span>

                <span>

                <?php

                if($g['estado'] == 'pendiente'){

                    echo "
                    <span class='estado estado-pendiente'>
                    Pendiente
                    </span>";

                } elseif($g['estado'] == 'en_revision'){

                    echo "
                    <span class='estado estado-revision'>
                    En revisión
                    </span>";

                } elseif($g['estado'] == 'vencida'){

                    echo "
                    <span class='estado estado-vencida'>
                    Vencida
                    </span>";

                } else {

                    echo "
                    <span class='estado estado-resuelto'>
                    Resuelto
                    </span>";
                }

                ?>

                </span>

            </div>

            <!-- FECHAS -->

            <div class="fila">

                <span>Fecha inicio</span>

                <span>
                    <?= $g['fecha_inicio'] ?>
                </span>

            </div>

            <div class="fila">

                <span>Fecha fin</span>

                <span>
                    <?= $g['fecha_fin'] ?>
                </span>

            </div>

        </div>

    </div>

    <!-- ACCIONES -->

    <div class="acciones">

        <a class="btn btn-eliminar"
        href="eliminar_garantia.php?id=<?= $g['id_garantia'] ?>"
        onclick="return confirm('¿Eliminar esta garantía?')">

            🗑️ Eliminar garantía

        </a>

    </div>

</div>

</body>
</html>