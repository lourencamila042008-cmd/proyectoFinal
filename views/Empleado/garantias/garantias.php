<?php
session_start();

// 🔒 ROLES
if (!isset($_SESSION["rol"]) || 
   ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado")) {
    header("Location: ../Auth/login.php");
    exit();
}

$esAdmin = $_SESSION["rol"] == "admin";

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
$vencidas = $conn->query("SELECT COUNT(*) as total FROM garantias WHERE estado = 'vencida'")->fetch_assoc()['total'];

$porVencer = $conn->query("
    SELECT COUNT(*) as total 
    FROM garantias 
    WHERE fecha_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)
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
<title>Garantías</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f4f6f9;
}

/* 🔝 TOPBAR */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn {
    background: #0f4c81;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
}

/* 🔎 BUSCADOR */
.search-box {
    padding: 15px 40px;
}

.search-box input {
    width: 300px;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

/* 📊 CARDS */
.stats {
    display: flex;
    gap: 15px;
    padding: 0 40px 20px;
}

.card {
    background: white;
    padding: 15px 25px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    font-weight: bold;
}

.vencidas { border-left: 5px solid red; }
.porvencer { border-left: 5px solid orange; }

/* CONTENEDOR */
.container {
    width: 95%;
    margin: auto;
}

/* TABLA */
.table-box {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #0f4c81;
    color: white;
    padding: 12px;
}

td {
    padding: 12px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

/* ESTADOS */
.estado {
    padding: 5px 10px;
    border-radius: 8px;
    color: white;
}

.estado-pendiente { background: #ffc107; color: black; }
.estado-revision { background: #17a2b8; }
.estado-resuelto { background: #28a745; }
.estado-vencida { background: #6c757d; }

tr.vencida {
    background: #eeeeee;
    opacity: 0.8;
}

/* BOTONES */
.btn-ver {
    background: #0f4c81;
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
    text-decoration: none;
}
</style>

</head>

<body>

<div class="topbar">
    <h1>Garantías</h1>
    <a class="btn" href="crear_garantia.php">+ Nueva garantía</a>
</div>

<!-- 🔎 BUSCADOR -->
<div class="search-box">
    <input type="text" id="buscar" placeholder="Buscar por producto, factura, estado...">
</div>

<!-- 📊 CONTADORES -->
<div class="stats">
    <div class="card vencidas">
        🔴 Vencidas: <?= $vencidas ?>
    </div>

    <div class="card porvencer">
        🟠 Por vencer (3 días): <?= $porVencer ?>
    </div>
</div>

<div class="container">
<div class="table-box">

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

<td><?= $g['id_garantia'] ?></td>
<td>#<?= $g['id_facturas'] ?></td>
<td><?= htmlspecialchars($g['nombre_producto']) ?></td>
<td><?= ucfirst($g['motivo']) ?></td>
<td><?= ucfirst($g['solucion']) ?></td>

<td>
<?php
if($g['estado'] == 'pendiente'){
    echo "<span class='estado estado-pendiente'>Pendiente</span>";
} elseif($g['estado'] == 'en_revision'){
    echo "<span class='estado estado-revision'>En revisión</span>";
} elseif($g['estado'] == 'resuelto'){
    echo "<span class='estado estado-resuelto'>Resuelto</span>";
} else {
    echo "<span class='estado estado-vencida'>Vencida</span>";
}
?>
</td>

<td><?= $g['fecha_inicio'] ?></td>
<td><?= $g['fecha_fin'] ?></td>

<td>
    <a class="btn-ver" href="ver_garantia.php?id=<?= $g['id_garantia'] ?>">Ver</a>
</td>

</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

<script>
// 🔎 BUSCADOR EN TIEMPO REAL
document.getElementById("buscar").addEventListener("keyup", function() {

    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaGarantias tbody tr");

    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });

});
</script>

</body>
</html>