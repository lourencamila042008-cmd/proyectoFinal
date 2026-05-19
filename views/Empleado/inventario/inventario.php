<?php
session_start();

// 🔒 PERMITIR admin y empleado
if (!isset($_SESSION["rol"]) ||
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {

    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

require_once __DIR__ . "/../../../config/db.php";

$conn = Database::Conectar();

$sql = "
    SELECT *
    FROM productos
    ORDER BY id_productos DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Inventario | InvoicePro</title>

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
    gap:20px;
    margin-bottom:30px;
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

/* ACTIONS */

.actions{
    display:flex;
    align-items:center;
    gap:15px;
    flex-wrap:wrap;
}

/* BUSCADOR */

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

/* BOTONES */

.btn{
    background:#16396b;
    color:white;
    text-decoration:none;
    padding:14px 22px;
    border-radius:14px;
    font-weight:600;
    transition:.3s;
    display:inline-block;
    border:none;
    cursor:pointer;
}

.btn:hover{
    transform:translateY(-2px);
}

.btn-back{
    background:#64748b;
}

.btn-back:hover{
    background:#475569;
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

/* STOCK */

.agotado{
    background:#fee2e2 !important;
}

.agotado td{
    color:#b91c1c;
    font-weight:600;
}

.bajo{
    background:#fef3c7 !important;
}

.bajo td{
    color:#92400e;
    font-weight:600;
}

/* BADGES */

.badge{
    padding:6px 12px;
    border-radius:10px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
    margin-left:10px;
}

.badge-agotado{
    background:#dc2626;
    color:white;
}

.badge-bajo{
    background:#f59e0b;
    color:white;
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
        font-size:35px;
    }

    .search{
        width:100%;
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

        <h1>Inventario</h1>

        <p>
            Gestiona productos y control de stock.
        </p>

    </div>

    <div class="actions">

        <!-- VOLVER -->

        <a class="btn btn-back"
        href="../dashboard_empleado.php">

            ← Volver

        </a>

        <!-- BUSCADOR -->

        <div class="search">

            <input type="text"
            id="buscar"
            placeholder="Buscar producto...">

        </div>

        <!-- SOLO ADMIN -->

        <?php if($esAdmin): ?>

        <a class="btn"
        href="agregar_producto.php">

            + Agregar producto

        </a>

        <?php endif; ?>

    </div>

</div>

<!-- TABLA -->

<div class="table-box">

    <h2 class="table-title">
        Lista de productos
    </h2>

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

        <?php while($p = $result->fetch_assoc()):

            $clase = "";

            if ($p["stock"] <= 0) {

                $clase = "agotado";

            } elseif ($p["stock"] <= $p["min_stock"]) {

                $clase = "bajo";
            }

        ?>

        <tr class="<?= $clase ?>">

            <td>
                #<?= $p["id_productos"] ?>
            </td>

            <td>
                <?= htmlspecialchars($p["nombre"]) ?>
            </td>

            <td>

                <?= $p["stock"] ?>

                <?php if($p["stock"] <= 0): ?>

                    <span class="badge badge-agotado">
                        AGOTADO
                    </span>

                <?php elseif($p["stock"] <= $p["min_stock"]): ?>

                    <span class="badge badge-bajo">
                        STOCK BAJO
                    </span>

                <?php endif; ?>

            </td>

            <td>
                $<?= number_format($p["precio_venta"], 0, ',', '.') ?>
            </td>

            <td>
                $<?= number_format($p["precio_compra"], 0, ',', '.') ?>
            </td>

            <td>
                <?= $p["min_stock"] ?>
            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<script>

// 🔎 BUSCADOR

document.getElementById("buscar")
.addEventListener("keyup", function(){

    let filtro =
    this.value.toLowerCase();

    let filas =
    document.querySelectorAll(
        "#tablaProductos tbody tr"
    );

    filas.forEach(fila => {

        let texto =
        fila.textContent.toLowerCase();

        fila.style.display =
        texto.includes(filtro)
        ? ""
        : "none";

    });

});

</script>

</body>
</html>